<?php
namespace app\models;

use \Execption;
use Yii;

class LoadPlayer{
	private $player_id;
	private $game;

	private $player_data = [];
	private $player = [];

	private $sql = '';
	function __construct($team_id, $player, $game){
		if(!empty($player)){
			$this->team_id = $team_id;
			$this->player_id = $player;
			$this->game = $game;		

			$this->load();
		}else throw new Exception("Ошибка!");
	}
	private function getPlayerDetails(){
		$player = ['player_id' => $this->player_id, 'game' => $this->game, 'earning' => 0, 'team_id' => $this->team_id];
		if(!empty($this->player)){
			foreach ($this->player as $value) {
				$prop = $value['property'];
				$item = $value['dataitem'][0]['item'];

				switch ($prop) {
					case 'Has_image':
						$player['image'] = DotaHelper::getImage(DotaHelper::tlLogoParser($item, $this->game));
						break;
					case 'Has_nationality':
						$player['country'] = $item;
						break;
					case 'Has_age':
						$player['age'] = preg_replace('/#0#/', '', $item);
						break;
					case 'Has_name':
						$player['name'] = $item;
						break;
					case 'Has_role':
						$player['role'] = preg_replace('/#0#/', '', $item);
						break;
					case 'Has_earnings':
						$player['earning'] = preg_replace('/[^0-9\.]+/', "", $item);
						break;
					default:
						break;
				}
			}
		}
		return $player;
	}
	private function load(){
		$api_call = DotaHelper::httpGet(DotaHelper::tlBuilder('api.php', $this->game), ['action' => 'browsebysubject', 'format' => 'json', 'subject' => $this->player_id], true);
		$player_data = json_decode($api_call, true);
		if(json_last_error() == JSON_ERROR_NONE){
			$this->player = $player_data['query']['data'];
		}
		$this->player_data = $this->getPlayerDetails();


	}
	public function overview(){
		if(!empty($this->player)){
			$overview = new ParseOverview($this->player_id, $this->game);
			$this->sql .= $overview->getSql();
		}
	}
	public function getSql(){
		return $this->sql;
	}
	public function getData(){
		return $this->player_data;
	}

}
?>