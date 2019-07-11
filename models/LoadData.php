<?php
namespace app\models;
use yii\db\Query;
use Yii;
class LoadData{
    const DOTA2_CODE = 0;
    const CSGO_CODE = 1;

    //const words = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_/.-()[]\\"\'*&^%$#@!~';

	public static function get($query){
        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_USERAGENT, "24cyber.ru info-bot");
        $data = curl_exec($ch); 
        curl_close($ch);
        return $data;
	}

    public static function team($id){
        $team = preg_replace('/\s/', '_', $id);
        $team = strtolower($team);
        return $team;
    }


    public static function parseLink($link){
        if(preg_match('/http:\/\/wiki\.teamliquid\.net\/(.+?)\/(.+?)$/', $link, $league)){
            $returns = [];
            if($league[1] == 'dota2'){
                $returns['game'] = self::DOTA2_CODE; 
            }else{
                $returns['game'] = self::CSGO_CODE; 
            }
            $returns['game_string'] = $league[1];
            $returns['league_id'] = $league[2];
            return $returns;
        }else{
            return false;
        }
    }




	private static function validateData($data){
        try{
             $data = json_decode($data);
             $values = $data->query;
             if(count($values->data) != 0){
             	return $values;
             }else{
             	return false;
             }

        }catch(Exception $e){
            return false;
        }
	}

	public static function getLogo($link, $game){
		if(!empty($link)){
        $data = self::get("http://wiki.teamliquid.net/".$game."/File:".$link);
        preg_match('/<a href="(.+)" class="internal"/', $data, $match);
        $logo = $match[1];
        if(!empty($logo)){
            $name = preg_replace('/http:\/\/wiki\.teamliquid\.net\//', "", $logo);
            $name = preg_replace('/\//', "_", $name);
            $d = file_get_contents($logo);
            $fp = fopen("/var/www/admin/www/24cyber.ru/web/img/logos/".$name, "w");
            fwrite($fp, $d);
            fclose($fp);
            $logo = '/img/logos/'.$name;
        }
        return $logo;

    }else{
    	return 0;
    }
	}
    public static function saveLogo($link){
        $name = preg_replace('/http:\/\/wiki\.teamliquid\.net\//', "", $link);
        $name = preg_replace('/\//', "_", $name);
        if(!file_exists('/var/www/admin/www/24cyber.ru/web/img/logos/'.$name)){
            $data = file_get_contents($link);
            $fp = fopen("/var/www/admin/www/24cyber.ru/web/img/logos/".$name, "w");
            fwrite($fp, $data);
            fclose($fp);
        }
        return '/img/logos/'.$name;
    }
    public static function dateType($string){
        $date = preg_replace('/1\/(\d+\/\d+\/\d+).*$/', "$1", $string);
        return date("Y-m-d", strtotime($date));
    }
    public static function toDate($date){
        $date = preg_replace('/1\/(\d+\/\d+\/\d+)\/(\d+)\/(\d+)\/(\d+).*$/', "$1 $2:$3:$4", $date);
        return date("Y-m-d H:i:s", strtotime($date) + 10800);
    }
    public static function toNum($str){
        $str = preg_replace('/\s/', '_', $str);
        $words = str_split(self::words);
        if(is_string($str)){
            $rstr = '';
            for ($i = 0; $i < strlen($str); $i++) { 
                $key = array_search($str[$i], $words);
                if($key < 10){
                    $rstr .= '0'.$key;
                }else{
                    $rstr .= (string) $key;
                }
            }
            return $rstr;
        }else{
            return;
        }
    }
    public static function toStr($num){
            $num = (string) $num;
            $rstr = '';
            $words = self::words;
            for ($i = 0; $i < strlen($num); $i += 2) { 
                $key = substr($num, $i, 2);
                $rstr .= $words[$key];
            }
            return $rstr;
    }
    public static function translate($text){
        $query = 'key=trnsl.1.1.20170106T133558Z.cfe1469ccb24549a.ed617d44072613f401574c42d181d0a9fa53b14e&lang=en-ru&format=html&text='.$text;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        $out = curl_exec($curl);
        curl_close($curl);
        return json_decode($out)->text[0];
        
    }
    public static function insertDuplicate($table, $data){
        $query = '';
        foreach ($data as $key => $value) {
            if(strlen($query) == 0){
                 $query .= $key.' = \''.$value.'\'';
            }else{
                 $query .= ', '.$key.' = \''.$value.'\'';
            }
           
        }
        return 'INSERT INTO '.$table.' SET '.$query.' ON DUPLICATE KEY UPDATE '.$query.'; ';

    }
        public static function insert($table, $data){
        $query = '';
        foreach ($data as $key => $value) {
            if(strlen($query) == 0){
                 $query .= $key.' = \''.$value.'\'';
            }else{
                 $query .= ', '.$key.' = \''.$value.'\'';
            }
           
        }
        return 'INSERT IGNORE INTO '.$table.' SET '.$query.'; ';

    }
        public static function update($table, $data, $where){
        $query = '';
        foreach ($data as $key => $value) {
            if(strlen($query) == 0){
                 $query .= $key.' = \''.$value.'\'';
            }else{
                 $query .= ', '.$key.' = \''.$value.'\'';
            }
           
        }
        return 'UPDATE '.$table.' SET '.$query.' WHERE '.$where.'; ';

    }
    public static function check($name){
        if(strtolower($name) == 'glossary' || strtolower($name) == 'tbd' || empty($name)){
            return false;
        }
        return true;
    }
    public static function getStream($team1, $team2){
        $twitch = [];

        $data = file_get_contents('https://api.twitch.tv/kraken/streams/?client_id=ymmcm9xbvwkx669wdk42bqygt67hic&game=Counter-Strike:%20Global%20Offensive&limit=10');
        $data = json_decode($data);

        foreach ($data->streams as $stream) {
            if(preg_match('/'.preg_replace('/_/', " ", $team1).'/', $stream->channel->status) || preg_match('/'.preg_replace('/_/', " ", $team2).'/', $stream->channel->status)){
                array_push($twitch, $stream->channel->name);
            }
        }
        return $twitch;
    }
}