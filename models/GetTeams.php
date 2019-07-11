<?php
namespace app\models;
use yii\db\Query;
use Yii;
class GetTeams{
	public function start($game, $type, $array){
		if($type == 1){

            foreach (array_chunk($this->getAll($game), 15) as $key) {
            $sql = $this->getAllTeamInfo($game, $key)[0];
            $sql2 = $this->getAllTeamInfo($game, $key)[1];
            Yii::$app->db->createCommand($sql)->execute();
        Yii::$app->db->createCommand($sql2)->execute();
            sleep(1.5);
       }
		}else{
			$sql = $this->getAllTeamInfo($game, $array)[0];
            $sql2 = $this->getAllTeamInfo($game, $array)[1];

        Yii::$app->db->createCommand($sql)->execute();
        Yii::$app->db->createCommand($sql2)->execute();
		}
		
	   
	    
		//print_r($sql);
		 
		 
	}
	private function getAll($game){
    $array = array();
    $data = file_get_contents("http://wiki.teamliquid.net/".$game."/Portal:Teams");
    preg_match('|<table width="100%" border="0" cellspacing="0"[^>]*?>(.*?)</table>|sei', $data, $mathes);
    preg_match_all('/<span class="team-template-text"><a(.+)<\/a><\/span>/', $mathes[0], $links);
    foreach ($links[1] as $link) {
      if(strpos($link, '(page does not exist)') === false){
        
        preg_match('/href="\/'.$game.'\/(.*)" t/', $link, $name);
        array_push($array, $name[1]);

      }
    }
    
    return $array;
  }
private function getMainInfo($data){
    try{
	$values = array('Has_location2' => 'none', 'Has_image' => 'none', 'Has_earnings' => 0, 'Has_id' => null);
	$keys = array('Has_earnings', 'Has_id', 'Has_image', 'Has_location', 'Has_location2', 'Has_name', 'Is_active');
	foreach ($data as $key) {
		if(in_array($key->property, $keys)){
           $values[$key->property] = $key->dataitem[0]->item;
		}
	}
    $values['Has_id'] = preg_replace('/\s/', "_", $values['Has_id']);
	return $values;
}catch (Exception $e) {
  return 0;
}
}
private function getPlayers($data){
    try{
	$values = array();
	foreach ($data as $key) {
		$playerD = array('Is_player' => 't');
		$keys = array('Has_flag', 'Has_id', 'Is_active', 'Is_player');
		if(preg_match('/player/', $key->subject)){
			foreach ($key->data as $player) {
				if(in_array($player->property, $keys)){
                    $playerD[$player->property] = $player->dataitem[0]->item;
		        }
			}
			if($playerD['Is_player'] == 't' && $playerD['Is_active'] == 't'){
				array_push($values, $playerD);
			}

			
		}
		
	}
	return $values;
}catch (Exception $e) {
    return 0;
}
}
  public function getTeamInfo($data){
  	   //	$data = file_get_contents("http://wiki.teamliquid.net/".$game."/api.php?action=browsebysubject&subject=".$name."&format=json");
   	$data = json_decode($data);
   	$array = array();

   	$array['info'] = $this->getMainInfo($data->query->data);
   	$array['players'] = $this->getPlayers($data->query->sobj);
    if($array['info'] == 0){
        return 0;
    }
   	


return $array;
  }
      private function getAllTeamInfo($game, $urls){
    	// страницы, содержимое которых надо получить

 $sql = '';
 $sql2 = '';
// инициализируем "контейнер" для отдельных соединений (мультикурл)
$cmh = curl_multi_init();
 
// массив заданий для мультикурл
// перебираем наши урлы
foreach ($urls as $url) {
    // инициализируем отдельное соединение (поток)
    $ch = curl_init('http://wiki.teamliquid.net/'.$game.'/api.php?action=browsebysubject&subject='.$url.'&format=json');
    // если будет редирект - перейти по нему
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // возвращать результат
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // не возвращать http-заголовок
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // таймаут соединения
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Access-Control-Allow-Origin' => '*']); 

    curl_setopt($ch, CURLOPT_USERAGENT, 'Bot (http://24cyber.ru/)');
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
    // таймаут ожидания
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // добавляем дескриптор потока в массив заданий
    $tasks[$url] = $ch;
    // добавляем дескриптор потока в мультикурл
    curl_multi_add_handle($cmh, $ch);
}
 
// количество активных потоков
$active = null;
// запускаем выполнение потоков
do {
    $mrc = curl_multi_exec($cmh, $active);
}
while ($mrc == CURLM_CALL_MULTI_PERFORM);
 
// выполняем, пока есть активные потоки
while ($active && ($mrc == CURLM_OK)) {
    // если какой-либо поток готов к действиям
    if (curl_multi_select($cmh) != -1) {
        // ждем, пока что-нибудь изменится
        do {
            $mrc = curl_multi_exec($cmh, $active);
            // получаем информацию о потоке
            $info = curl_multi_info_read($cmh);
            // если поток завершился
            if ($info['msg'] == CURLMSG_DONE) {
                $ch = $info['handle'];
                // ищем урл страницы по дескриптору потока в массиве заданий
                $url = array_search($ch, $tasks);
                // забираем содержимое
                try{
                $teamI = $this->getTeamInfo(curl_multi_getcontent($ch));
                if($teamI != 0){
                
                if(!empty($teamI['info']['Has_id'])){
                	if(empty($teamI['info']['Has_name'])){
                		$teamI['info']['Has_name'] = $teamI['info']['Has_id'];
                	}
                    //print_r($teamI['players']);
                 $sql .= 'INSERT INTO teams_'.$game.' VALUES("", "'.$teamI['info']['Has_id'].'", "'.$teamI['info']['Has_name'].'", "'.$teamI['info']['Has_location'].'", "'.$teamI['info']['Has_location2'].'", '.$teamI['info']['Has_earnings'].', "'.$teamI['info']['Has_image'].'", default) ON DUPLICATE KEY UPDATE name = "'.$teamI['info']['Has_name'].'", location = "'.$teamI['info']['Has_location'].'", location2 = "'.$teamI['info']['Has_location2'].'", earning = '.$teamI['info']['Has_earnings'].', image = "'.$teamI['info']['Has_image'].'"; ';
                }
                if($teamI['players'] != 0){
                $string = '';
                $string2 = '';
                for($i = 0; $i < 5; $i++){
                    if(!empty($teamI['players'][$i])){
                        $string .= ', "'.$teamI['players'][$i]['Has_id'].'", "'.$teamI['players'][$i]['Has_flag'].'"';
                    }else{
                        $string .= ', "none", "none"';
                    }
                }
                for($i = 0; $i < 5; $i++){
                     $v = $i + 1;
                     if($i == 0){
                    if(!empty($teamI['players'][$i])){

                        $string2 .= 'player'.$v.'_name = "'.$teamI['players'][$i]['Has_id'].'", player'.$v.'_flag = "'.$teamI['players'][$i]['Has_flag'].'"';
                    }else{
                        $string2 .= 'player'.$v.'_name = "none", player'.$v.'_flag = "none"';
                    }                    
                     }else{
                    if(!empty($teamI['players'][$i])){

                        $string2 .= ', player'.$v.'_name = "'.$teamI['players'][$i]['Has_id'].'", player'.$v.'_flag = "'.$teamI['players'][$i]['Has_flag'].'"';
                    }else{
                        $string2 .= ', player'.$v.'_name = "none", player'.$v.'_flag = "none"';
                    }
                }
                }
                $sql2 .= 'INSERT INTO '.$game.'_team_players VALUES("'.$teamI['info']['Has_id'].'"'.$string.') ON DUPLICATE KEY UPDATE '.$string2.'; ';
               }
           }
       }catch(Exception $e){

       }
                
                //print_r($this->getTeamInfo(curl_multi_getcontent($ch))['info']['Has_name']);

                // удаляем поток из мультикурла
                curl_multi_remove_handle($cmh, $ch);
                // закрываем отдельное соединение (поток)
                curl_close($ch);
            }
        }
        while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}
 
// закрываем мультикурл
curl_multi_close($cmh);
return [$sql, $sql2];
    }
}


?>