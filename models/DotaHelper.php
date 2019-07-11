<?php
namespace app\models;
use yii\db\Query;
use Yii;
class DotaHelper{
    const DOTA2_CODE = 0;
    const CSGO_CODE = 1;

    public static function tlBuilder($link, $game){
        $game = $game == 0 ? 'dota2' : 'counterstrike';
        return 'http://wiki.teamliquid.net/'.$game.'/'.$link;
    }

    public static function httpGet($link, $params = null, $gzip = false){
        $url = $link;
        if(!empty($params)){
            $question = true;
            foreach ($params as $key => $value) {
                if($question){
                    $url .= '?';
                    $question = false;
                }else{
                    $url .= '&';
                }
                if(empty($value)){
                    $url .= $key;
                }else{
                    $url .= $key.'='.$value;
                }
                
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "24cyber bot(https://24cyber.ru) admin (trutnev.seva@yandex.ru)");
        curl_setopt ($ch, CURLOPT_PROXY, "45.55.233.201:1228");
            //curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Encoding: gzip']);
            curl_setopt($ch,CURLOPT_ENCODING , "gzip");           

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch); 
        //$info = curl_getinfo($ch);
        //print_r($data);
        curl_close($ch);
        return $data;
    }

    public static function apiLeagueDateParser($date_bad){
        $date = preg_replace('/1\/(\d+\/\d+\/\d+).*$/', "$1", $date_bad);
        return date("Y-m-d", strtotime($date));       
    }
    public static function apiMatchDateParser($date_bad){
        $date = preg_replace('/1\/(\d+\/\d+\/\d+)\/(\d+)\/(\d+)\/(\d+).*$/', "$1 $2:$3:$4", $date_bad);
        return date("Y-m-d H:i:s", strtotime($date) + 10800);     
    }
    public static function tlLogoParser($logo, $game){
        if(!empty($logo)){
            $img_page = self::httpGet(self::tlBuilder('File:'.$logo, $game));
            if(!empty($img_page)){
                preg_match('/<a href="(.+)" class="internal"/', $img_page, $match);
                if(isset($match[1])){
                    $img = $match[1];
                    if(!empty($img)){
                        $logo = preg_replace('/#\d#/', "", $logo);
                        return ['link' => $img, 'name' => $logo];
                    }
                }
            }
        }
        return null;
    }
    public static function getImage($img = null){
        if(!empty($img)){
            $file_path = Yii::getAlias('@webroot')."/img/logos/".$img['name'];
            if(!file_exists($file_path)){
                $bitmap = self::httpGet($img['link']);
                if(!empty($bitmap)){
                    $file = fopen(Yii::getAlias('@webroot')."/img/logos/".preg_replace('/\//', '_', $img['name']), "w");
                    fwrite($file, $bitmap);
                    fclose($file);
                    return '/img/logos/'.$img['name'];
                } 
            }else return '/img/logos/'.$img['name'];
        }
        return '/img/logos/empty_logo.png';
    }
    public static function check($name){
        if(strtolower($name) == 'glossary' || strtolower($name) == 'tbd' || empty($name) || strtolower($name) == 'definitions'){
            return false;
        }
        return true;
    }

}