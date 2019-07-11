<?php
namespace app\models;

use \Execption;
use Yii;

class LoadTeam{
	private $team_id;
	private $game;

	private $team = [];
	private $page = null;
	private $wikitext = null;
	function __construct($team, $game){
		if(!empty($team)){
			$this->team_id = $team;
			$this->game = $game;			

			$this->load();
		}else throw new Exception("Ошибка!");
	}
	private function getTeamDetails(){
		$team = ['team_id' => $this->team_id, 'name' => $this->team_id, 'game' => $this->game, 'earning' => 0];
		if(!empty($this->team)){
			foreach ($this->team as $value) {  
				$prop = $value['property'];
				$item = $value['dataitem'][0]['item'];

				switch ($prop) {
					case 'Has_image':
						$team['logo'] = DotaHelper::getImage(DotaHelper::tlLogoParser($item, $this->game));
						break;
					case 'Has_location':
						$country1 = $item;
						break;
					case 'Has_location2':
						$country2 = $item;
						break;
					case 'Has_name':
						$team['name'] = $item;
						break;
					case 'Was_created':
						preg_match('/\d{4}/', $item, $m);
						$team['created'] = $m[0]; /*preg_replace('/<.+?>/s', '', preg_replace('/\[\[.+?\]\]/s', '', $item));*/
						break;
					case 'Has_captain':
						$team['captain'] = preg_replace('/\[|\]/', '', preg_replace('/^\[\[.+?\]\]/s', '', preg_replace('/\'/', '', $item)));
						break;
					case 'Has_earnings':
						$team['earning'] = preg_replace('/[^0-9\.]+/', "", $item);
						break;
					default:
						break;
				}
				if(!empty($country2)){
					$team['flag'] = $country2;
				}else if(!empty($country1)){
					$team['flag'] = $country1;
				}
			}
			if(empty($team['logo']) || $team['logo'] == '/img/logos/empty_logo.png'){
				if($this->game == DotaHelper::CSGO_CODE){
					$team['logo'] = '/img/csgo_game.png';
				}else if($this->game == DotaHelper::DOTA2_CODE){
					$team['logo'] = '/img/dota2_game.png';
				}
			}
		}
		return $team;
	}
	private function getDotabuff(){
		if(!empty($this->wikitext)){
			preg_match('/teamid=(\d+?)\n/', $this->wikitext, $dotabuff);
			if(!empty($dotabuff)){
				return $dotabuff[1];
			}
		}
		return null;
	}
	private function getPlayersList(){
		$players = [];
		if(!empty($this->wikitext)){
			$parsed_wikitext = preg_replace('/\n/', '', $this->wikitext);
			preg_match('/\{\{ActiveSquad\|.+?\}\}\}\}/x', $parsed_wikitext, $activeSquad);
			if(!empty($activeSquad)){
				$activeSquad = preg_replace('/<ref.+?>/s', '', $activeSquad[0]);
				preg_match_all('/\{\{SquadPlayer.+?\}\}/x', $activeSquad, $player);
				if(!empty($player)){
					if(!empty($player[0])){
						foreach ($player[0] as $player) {
							if(!preg_match('/\|Coach\|/', $player)){
								preg_match('/player=(.+?)\|/x', $player, $player_id);
								if(!empty($player_id)){
									$player_id = trim($player_id[1]);
									if(!preg_match('/<!--.+?-->/s', $player_id)){
										array_push($players, preg_replace('/\s/', '_', $player_id));
									}
								}
							}
						}
					}
				}
			}
		}
		return $players;
	}
	private function load(){
		$api_call = DotaHelper::httpGet(DotaHelper::tlBuilder('api.php', $this->game), ['action' => 'browsebysubject', 'format' => 'json', 'subject' => $this->team_id], true);
		$team_data = json_decode($api_call, true);
		$this->team = [];
		if(json_last_error() == JSON_ERROR_NONE){
			$this->team = $team_data['query']['data'];
		}
		$this->team_data = $this->getTeamDetails();
		
		if(!empty($this->team)){
			$api_wikitext = DotaHelper::httpGet(DotaHelper::tlBuilder('api.php', $this->game), 
				['action' => 'parse', 'format' => 'json', 'prop' => 'wikitext', 'redirects', 'page' => $this->team_id], true);
			$api_wikitext = json_decode($api_wikitext, true);
			if(json_last_error() == JSON_ERROR_NONE){
				if(empty($api_wikitext['error'])){
					$this->wikitext = $api_wikitext['parse']['wikitext']['*'];
					if($this->game == DotaHelper::DOTA2_CODE){
						$this->team_data['spec_id'] = $this->getDotabuff();
					}
				}
			}
		}
		$this->saveTeam();	
			
		
	}
	private function saveTeam(){
		
		$team = Team::findOne(['team_id' => $this->team_id, 'game' => $this->game]);

		$updated = 0;
		if(!$team){
			$team = new Team();
		}else{
			$updated = strtotime($team->updated); 
		}


		$this->team_data['updated'] = date('Y-m-d H:i:s');
		$team->attributes = $this->team_data;
		//$team->delete();
		$team->save();
		$sql = '';

		if(!empty($this->wikitext)){
			$this->players = $this->getPlayersList();
			//print_r($this->players);

			$players = Player::find()->where(['team_id' => $team->team_id])->asArray()->all();
			//print_r($players);

			foreach ($this->players as $player) {
				$loadPlayer = new LoadPlayer($team->team_id, $player, $this->game);
				if($updated + 30 * 24 * 60 * 60 <= time()){
					$loadPlayer->overview();
				}
				$sql .= $loadPlayer->getSql();
				$find = false;
				foreach ($players as $key => $dbPlayer) {
					if($dbPlayer['player_id'] == $player){
						echo 3;
						$find = true;
						$sql .= Yii::$app->db->createCommand()->update('players', $loadPlayer->getData(), 'id = '.$dbPlayer['id'])->getRawSql().'; ';
						unset($players[$key]);
						sort($players);
						break;
					}
				}
				if(!$find){
					echo 1;
					$sql .= LoadData::insert('players', $loadPlayer->getData());
				}
			}
			foreach ($players as $player) {
				echo 2;
					$sql .= Yii::$app->db->createCommand()->delete('players', 'id = '.$player['id'])->getRawSql().'; ';
				}	
			if($updated + 30 * 24 * 60 * 60 <= time()){
				echo 6;
				$overview = new ParseOverview($this->team_id, $this->game);
				$sql .= $overview->getSql();
			}
			Yii::$app->db->createCommand($sql)->execute();	
		}
	}
}
?>