<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\League;








class leaguedata{
		private function toDateP($date){
		$date = preg_replace('/1\/(\d+\/\d+\/\d+).*$/', "$1", $date);
		return date("Y-m-d", strtotime($date));


	}
      private function toDate($date){
		$date = preg_replace('/1\/(\d+\/\d+\/\d+)\/(\d+)\/(\d+)\/(\d+).*$/', "$1 $2:$3:$4", $date);
		return date("Y-m-d H:i:s", strtotime($date) + 10800);


	}
	public function getInfo($data, $type){
		$array = array();
    $properties = array("Has_city", "Has_country", "Has_id", "Has_name", "Has_image", "Has_prizepool_in_usd");
    foreach ($data as $key) {
    	if($key->property == "Has_date" || $key->property == "Has_end_date"){
    		$array[$key->property] = $this->toDateP($key->dataitem[0]->item);

    	}
    	else if(in_array($key->property, $properties)){
    		$array[$key->property] = $key->dataitem[0]->item;

    	}
    }
    $array['Has_date'] = empty($array['Has_date']) ? date("Y-m-d H:i:s", strtotime('-20 day')) : $array['Has_date'];
    $array['Has_image'] = empty($array['Has_image']) ? 'none' : $this->getImage($array['Has_image'], $type);
    $array['Has_prizepool_in_usd'] = empty($array['Has_prizepool_in_usd']) ? '0' : $array['Has_prizepool_in_usd'];
    $array['Has_prizepool_in_usd'] = preg_replace('/[^0-9]+/', "", $array['Has_prizepool_in_usd']);
    return $array;
	}
	private function getImage($image, $game){
       $data = file_get_contents("http://wiki.teamliquid.net/".$game."/File:".$image);
if(!empty($image)){
	       preg_match('/<a href="(.+)" class="internal"/', $data, $match);
       return $match[1];

}
else{
	return "none";
}

	}
	public function getMatches($data){
		$array = array();
		$properties = array("Has_team_left", "Has_team_right", "Is_finished", "Is_map_number", "Has_team_right_score", "Has_team_left_score");
		foreach ($data as $key) {
			$values = array('Has_map_date' => null);
			$values['Is_map_number'] = 0;
			if(preg_match('/_vs_/', $key->subject)){
				foreach ($key->data as $obj) {
					if($obj->property == "Has_map_date"){
                       $values['Has_map_date'] = $obj->dataitem[0]->item;
					}
					else if(in_array($obj->property, $properties)){
                      $values[$obj->property] = $obj->dataitem[0]->item;
					}
				}
				if($values['Has_team_right_score'] < $values['Has_team_left_score']){
					$values['winner'] = 1;
				}
				else if($values['Has_team_right_score'] > $values['Has_team_left_score']){
					$values['winner'] = 2;
				}
				else{
					$values['winner'] = 0;
				}


				$values['Has_team_right'] = preg_replace('/#0#/', "", $values['Has_team_right']);
				$values['Has_team_left'] = preg_replace('/#0#/', "", $values['Has_team_left']);
				if($values['Has_team_right'] == "Definitions"){
					$values['Has_team_right'] = "TBD";

				}
				if($values['Has_team_left'] == "Definitions"){
					$values['Has_team_left'] = "TBD";

				}
				$values['score'] = $values['Has_team_left_score'].'-'.$values['Has_team_right_score'];
                
						if(strlen($values['Has_map_date']) < 10 || empty($values['Has_map_date'])){
							
                          $values['Has_map_date'] = date("Y-m-d H:i:s", strtotime('-2 day'));
						}
						else{
							$values['Has_map_date'] = $this->toDate($values['Has_map_date']);
							
						}
						array_push($array, $values);
                unset($values['Has_team_left_score']);
                unset($values['Has_team_right_score']);
                
			}
			
		}

return $array;
	}
	public function getPlace($data){
		$array = array();
		foreach ($data as $key) {
			if(preg_match('/ranking_to_be_determined/', $key->subject)){
                return array();

			}
			else if(preg_match('/ranking/', $key->subject)){
                   $values = array();
				foreach ($key->data as $obj) {
					if($obj->property == "Has_placement"){
						$values['place'] = $obj->dataitem[0]->item;

					}
					else if($obj->property == "Has_team_page"){
                       $values['id'] = preg_replace('/#0#/', "", $obj->dataitem[0]->item);
					}
				}
				array_push($array, $values);
			}
		}
		return $array;
	}
	    public function get($name, $game){
	    	$type = 0;
    	if($game == "csgo"){
$type = "counterstrike";
}
else{
$type = "dota2";
}
$data = file_get_contents("http://wiki.teamliquid.net/".$type."/api.php?action=browsebysubject&subject=".$name."&format=json");
$data = json_decode($data);


$return = array();



$return['info'] = $this->getInfo($data->query->data, $type);
$return['place'] = $this->getPlace($data->query->sobj);
$return['matches'] = $this->getMatches($data->query->sobj);

return json_encode($return);


    }
}


?>

