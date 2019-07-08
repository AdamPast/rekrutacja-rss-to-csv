<?php
namespace AdamPastalenzRekrutacjaHRtec\Src;

class Controller{

    public function simple($url,$path){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $test = libxml_use_internal_errors(true);
        try {
            $content = new \SimpleXmlElement($data, LIBXML_NOCDATA);
        }
        catch (\Exception $e) {echo "Link nie jest prawidłowy"; return false;}

        libxml_clear_errors();
        libxml_use_internal_errors($test);
        $headers = array('Tytuł', 'Opis', 'Link', 'Data publikacji');
        $fp = fopen($path,'w');

        if($fp && $content){
            fputcsv($fp,$headers);
            setlocale (LC_ALL, "pl_PL");
            foreach ($content->channel->item as $item){
                $title = $item -> title;
                $postDate = $item -> pubDate;
                $pubDate = strftime( "%d %B %y %T",strtotime($postDate));
                $link = $item -> link;
                $description = strip_tags(html_entity_decode($item -> description));
                preg_match_all("|<[^>]+>(.*)</[^>]+>|U",$description);
                $rssArray = array($title,$description,$link,$pubDate);

                fputcsv($fp, $rssArray);
            }
            fclose($fp);
            echo "Plik ".$path." został pomyślnie utworzony.";

        }
    }
    public function extend($url,$path){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $test = libxml_use_internal_errors(true);
        try {
            $content = new \SimpleXmlElement($data, LIBXML_NOCDATA);
        }
        catch (\Exception $e) {echo "Link nie jest prawidłowy"; return false;}

        libxml_clear_errors();
        libxml_use_internal_errors($test);

        $headers = array('Tytuł', 'Opis', 'Link', 'Data publikacji');

        if(file_exists($path)){
            $fp = fopen($path,'a');
        }else{
            $fp = fopen($path,'w');
            fputcsv($fp,$headers);
        }
        if($fp && $content){
            setlocale (LC_ALL, "pl_PL");
            foreach ($content->channel->item as $item){
                $title = $item -> title;
                $postDate = $item -> pubDate;
                $pubDate = strftime( "%d %B %y %T",strtotime($postDate));
                $link = $item -> link;
                $description = strip_tags(html_entity_decode($item -> description));
                preg_match_all("|<[^>]+>(.*)</[^>]+>|U",$description);
                $rssArray = array($title,$description,$link,$pubDate);

                fputcsv($fp, $rssArray);
            }
            fclose($fp);
            echo "Pola zostaly pomyślnie dodane do pliku " . $path;


        }
    }
}
