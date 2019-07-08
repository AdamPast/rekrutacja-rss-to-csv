<?php
namespace AdamPastalenzRekrutacjaHRtec\Src;
include ('controller.php');

if($argc == 4){

switch ($argv[1]){
    case "csv:simple":
        $url = $argv[2];
        $path = $argv[3];
        $rss = new Controller();
        $rss->simple($url,$path);
        break;
    case "csv:extend":
        $url = $argv[2];
        $path = $argv[3];
        $rss = new Controller();
        $rss->extend($url,$path);
        break;
    default: echo "Invalid argument";
    }
}else{
    echo "Polecenie nie jest prawidłowe";
}