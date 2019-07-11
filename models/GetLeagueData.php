<?php
namespace app\models;

use Yii;
use yii\base\Model;


class GetLeagueData{
    private function toDate($date){
		$date = preg_replace('/1\/(\d+\/\d+\/\d+)\/(\d+)\/(\d+)\/(\d+).*$/', "$1 $2:$3:$4", $date);
		return date("Y-m-d H:i:s", strtotime($date) + 10800);


	}
	private function getLeagueJson($query){
        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_USERAGENT, "24cyber.ru info-bot");
        $data = curl_exec($ch); 
        curl_close($ch);
        return $data;
	}
	private function validateData($data){
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
	private function getLogo($link){
		if(!empty($link)){
        $ch = curl_init("http://wiki.teamliquid.net/".$this->game."/File:".$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_USERAGENT, "24cyber.ru info-bot");
        $data = curl_exec($ch); 
        curl_close($ch);
        preg_match('/<a href="(.+)" class="internal"/', $data, $match);
        return $match[1];
    }else{
    	return 0;
    }
	}
	private function dateType($string){
	    $date = preg_replace('/1\/(\d+\/\d+\/\d+).*$/', "$1", $string);
		return date("Y-m-d", strtotime($date));
    }
	private function getLeagueDetails($object){
		$leagueDetails = [];
		foreach ($object as $value) {
			$prop = $value->property;
			$item = $value->dataitem[0]->item;
			switch ($prop) {
				case 'Has_date':
					$leagueDetails['date_start'] = $this->dateType($item);
					break;
				case 'Has_end_date':
					$leagueDetails['date_end'] = $this->dateType($item);
					break;
				case 'Has_image':
					$leagueDetails['image'] = LoadData::getLogo(preg_replace('/#6#/', '', $item));
					break;
				case 'Has_id':
					$leagueDetails['id'] = $item;
					break;
				case 'Has_name':
					$leagueDetails['name'] = $item;
					break;
				case 'Has_prizepool_in_usd':
					$leagueDetails['prizepool'] = preg_replace('/[^0-9]+/', "", $item);
					break;
				default:
					break;
			}
			
		}
		return $leagueDetails;
	}
	private function getMatchesDetails($object){
		$mathesDetails = [];
       foreach ($object as $value) {
       	   if(preg_match('/_vs_/', $value->subject)){
       	   	$values = ['team1' => [], 'team2' => []];
       	   	   foreach ($value->data as $params) {
       	   	   		$prop = $params->property;
			        $item = $params->dataitem[0]->item;
       	   	   	switch ($prop) {
				case 'Has_map_date':
					$values['date'] = $this->toDate($item);
					break;
				case 'Has_team_left':
					$values['team1']['id'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_team_left_score':
					$values['team1']['score'] = $item;
					break;
				case 'Has_team_right':
					$values['team2']['id'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_team_right_score':
					$values['team2']['score'] = $item;
					break;
				default:
					break;
			}
       	   	   }
       	   	   array_push($mathesDetails, $values);
       	   }

       }
       return $mathesDetails;
	}
	private function getTeams($object){
		$teamsData = [];
       foreach ($object as $value) {
       	   if(preg_match('/ranking/', $value->subject)){
       	   	$values = ['players' => []];
       	   	   foreach ($value->data as $params) {
       	   	   		$prop = $params->property;
			        $item = $params->dataitem[0]->item;
       	   	   	switch ($prop) {
				case 'Has_placement':
					$values['place'] = $item;
					break;
				case 'Has_prizemoney':
					$values['prize'] = $item;
					break;
				case 'Has_team_page':
					$values['team'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_player_pages':
					foreach ($params->dataitem as $player) {
						array_push($values['players'], preg_replace('/#0#/', '', $player->item));
					}
					break;
				default:
					break;
			}
       	   	   }
       	   	   array_push($teamsData, $values);
       	   }

       }
       return $teamsData;
	}
	public function start($game, $tournament){
           $league = $this->getLeagueJson('http://wiki.teamliquid.net/'.$game.'/api.php?action=browsebysubject&subject='.$tournament.'&format=json');
           $league = $this->validateData($league);
           $this->game = $game;
           if($league){
              $leagueDetails = $this->getLeagueDetails($league->data);
              $matches = $this->getMatchesDetails($league->sobj);
              $teams = $this->getTeams($league->sobj);
              $data = ['league' => $leagueDetails, 'matches' => $matches, 'teams' => $teams];
              return $data;
              // print_r($leagueDetails);
              // print_r($matches);
              // print_r($teams);
           }else{
           	return '#002';
           }

	}
}

?>

