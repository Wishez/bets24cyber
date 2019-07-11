<?php
namespace app\models;

use Yii;
use app\models\LoadData;
use app\models\Match;
/**
 * This is the model class for table "team".
 *
 * @property integer $id_team
 * @property integer $id_steam_team
 * @property string $team_name
 * @property string $country_code
 * @property integer $team_logo
 */
class DotaGame
{

const RADIANT_CODE = 1;
const DIRE_CODE = 0;

const BAN_CODE = 0;
const PICK_CODE = 1;

const GPM_CODE = 0;
const GOLD_CODE = 1;
const XPM_CODE = 2;
const NW_CODE = 3;
const XP_CODE = 4;

private $data;

private $game = [];
private $picks = [];
private $bans = [];

private $players = [];

private $graph = [self::RADIANT_CODE => [], self::DIRE_CODE => []];

function __construct(){
	for ($i = 0; $i < 5; $i++) {
		$this->graph[self::DIRE_CODE][$i] = 0;
		$this->graph[self::RADIANT_CODE][$i] = 0;
	}
}
private function getPlayer($player){
		$playerData = [];
		$playerData['account_id'] = $player->account_id;
		$playerData['hero_id'] = $player->hero_id;
		$playerData['kills'] = $player->kills;
		$playerData['death'] = isset($player->death) ? $player->death : $player->deaths;
		$playerData['assists'] = $player->assists;
		$playerData['last_hits'] = $player->last_hits;
		$playerData['denies'] = $player->denies;
		$playerData['gold'] = $player->gold;
		$playerData['gpm'] = $player->gold_per_min;
		$playerData['xpm'] = $player->xp_per_min;
		$playerData['level'] = $player->level;
		$playerData['net_worth'] = isset($player->net_worth) ? $player->net_worth : $player->gold_spent;
		if(isset($player->position_x)){
			$playerData['position_x'] = $player->position_x;
		}
		if(isset($player->position_y)){
			$playerData['position_y'] = $player->position_y;
		}
		$playerData['items'] = [];


		for ($g = 0; $g < 6; $g++) { 
			array_push($playerData['items'], isset($player->{'item'.$g}) ? $player->{'item'.$g} : $player->{'item_'.$g});
		}
		return $playerData;
}

public function playerData($team, $player){
	$playerData = $this->getPlayer($player);
	$playerData['team'] = $team;

	$this->graph[$team][self::GPM_CODE] += $playerData['gpm'];
	$this->graph[$team][self::XPM_CODE] += $playerData['xpm'];
	$this->graph[$team][self::GOLD_CODE] += $playerData['gold'];
	$this->graph[$team][self::NW_CODE] += $playerData['net_worth'];
	$this->graph[$team][self::XP_CODE] += round($playerData['xpm'] * $this->game['game_time'] / 60);

	for ($i = 0; $i < count($this->data->players); $i++) { 
		if($this->data->players[$i]->account_id == $playerData['account_id']){
			$playerData['name'] = $this->data->players[$i]->name;
			break;
		}
	}
	return $playerData;
}

public function LoadLive($data){
	$this->data = $data;

	$this->game['match_id'] = $data->match_id;

	$this->game['radiant_name'] = $data->radiant_team->team_name;
	$this->game['radiant_score'] = $data->scoreboard->radiant->score;
	$this->game['radiant_tower'] = $data->scoreboard->radiant->tower_state;
	$this->game['radiant_id'] = $data->radiant_team->team_id;
	$this->game['radiant_rax'] = $data->scoreboard->radiant->barracks_state;

	$this->game['dire_name'] = $data->dire_team->team_name;
	$this->game['dire_score'] = $data->scoreboard->dire->score;
	$this->game['dire_tower'] = $data->scoreboard->dire->tower_state;
	$this->game['dire_id'] = $data->dire_team->team_id;
	$this->game['dire_rax'] = $data->scoreboard->dire->barracks_state;

	if($data->series_type == 0){
		$this->game['series'] = 1;
	}else if($data->series_type == 1){
		$this->game['series'] = 3;
	}else if($data->series_type == 2){
		$this->game['series'] = 5;
	}
	
	$this->game['game_time'] = $data->scoreboard->duration;

	foreach (isset($this->data->scoreboard->radiant->picks) ? $this->data->scoreboard->radiant->picks : [] as $value) {
		array_push($this->picks, ['team' => self::RADIANT_CODE, 'hero_id' => $value->hero_id]);
	}
	foreach (isset($this->data->scoreboard->dire->picks) ? $this->data->scoreboard->dire->picks : [] as $value) {
		array_push($this->picks, ['team' => self::DIRE_CODE, 'hero_id' => $value->hero_id]);
	}
	foreach (isset($this->data->scoreboard->dire->bans) ? $this->data->scoreboard->dire->bans : [] as $value) {
		array_push($this->bans, ['team' => self::DIRE_CODE, 'hero_id' => $value->hero_id]);
	}
	foreach (isset($this->data->scoreboard->radiant->bans) ? $this->data->scoreboard->radiant->bans : [] as $value) {
		array_push($this->bans, ['team' => self::RADIANT_CODE, 'hero_id' => $value->hero_id]);
	}

	//print_r($this->game);

	////print_r($this->picks);
	////print_r($this->bans);
	foreach ($this->data->scoreboard->radiant->players as $player) {
		array_push($this->players, $this->playerData(self::RADIANT_CODE, $player));
	}
	foreach ($this->data->scoreboard->dire->players as $player) {
		array_push($this->players, $this->playerData(self::DIRE_CODE, $player));
	}
	//print_r($this->graph);
	print_r($this->graph);
}
public function LoadMatch($data){
	$this->data = $data;

	$this->game['match_id'] = $data->match_id;

	$this->game['radiant_name'] = $data->radiant_name;
	$this->game['radiant_score'] = $data->radiant_score;
	$this->game['radiant_tower'] = $data->tower_status_radiant;
	$this->game['radiant_id'] = $data->radiant_team_id;

	$this->game['dire_name'] = $data->dire_name;
	$this->game['dire_score'] = $data->dire_score;
	$this->game['dire_tower'] = $data->tower_status_dire;
	$this->game['dire_id'] = $data->dire_team_id;

	$this->game['game_time'] = $data->duration;
	$this->game['radiant_win'] = $data->radiant_win ? 1 : 0;

	$this->game['dire_rax'] = $data->barracks_status_dire;
	$this->game['radiant_rax'] = $data->barracks_status_radiant;
	////print_r($this->game);
	//print_r($this->players);

}

private function getQuery($save = 0){
	$sql = 'DELETE FROM dota_picks_bans WHERE match_id = "'.$this->game['match_id'].'"; ';
	$sql .= 'DELETE FROM dota_players WHERE match_id = "'.$this->game['match_id'].'"; ';
	//$sql .= 'DELETE FROM dota_players_items WHERE match_id = "'.$this->game['match_id'].'"; ';

	foreach ($this->picks as $value) {
		$value['match_id'] = $this->game['match_id'];
		$value['action_type'] = self::PICK_CODE;
		$sql .= LoadData::insert('dota_picks_bans', $value);
	}
	foreach ($this->bans as $value) {
		$value['match_id'] = $this->game['match_id'];
		$value['action_type'] = self::BAN_CODE;
		$sql .= LoadData::insert('dota_picks_bans', $value);
	}

	foreach ($this->players as $player) {
		$player['match_id'] = $this->game['match_id'];
		$items = Match::find()
		->from('dota_players_items')->where(['account_id' => $player['account_id'], 'match_id' => $player['match_id']])->orderBy('item_pos')->asArray()->all();

		for($i = 0; $i < count($player['items']); $i++){
			$item = [];
			$item['account_id'] = $player['account_id'];
			$item['match_id'] = $this->game['match_id'];
			$item['item_id'] = $player['items'][$i];
			$item['time'] = (int)($this->game['game_time'] / 60);
			$item['item_pos'] = $i;

			if(isset($items[$i])){
				if($items[$i]['item_id'] != $item['item_id']){
					$sql .= LoadData::update('dota_players_items', $item, 
						'match_id = "'.$this->game['match_id'].'" AND account_id = "'.$player['account_id'].'" AND item_pos = '.$i);
				}
			}else{
				$sql .= LoadData::insert('dota_players_items', $item);
			}
		}
		unset($player['items']);
		$sql .= LoadData::insert('dota_players', $player);
	}

	for($i = 0; $i < count($this->graph[self::RADIANT_CODE]); $i++){
		$schedule = [];
		$schedule['match_id'] = $this->game['match_id'];
		$schedule['data'] = $this->graph[self::RADIANT_CODE][$i] - $this->graph[self::DIRE_CODE][$i];
		$schedule['type'] = $i;
		$schedule['game_time'] = $this->game['game_time'];
		$sql .= LoadData::insert('dota_game_graph', $schedule);

	}
	return $sql;
}

public function save($id){
	$game = $this->game;
	$game['m_id'] = $id;


	$sql = LoadData::insert('dota_games', $game);


	$sql .= $this->getQuery(1);

	Yii::$app->db->createCommand($sql)->execute();
}
public function update($active = 1){
	$game = $this->game;

	$game['active'] = $active;

	$sql = LoadData::update('dota_games', $game, 'match_id = '.$this->game['match_id']);
	$sql .= $this->getQuery();

	Yii::$app->db->createCommand($sql)->execute();
}
public function updateLast(){
	$this->game['active'] = 0;
	$sql = LoadData::update('dota_games', $this->game, 'match_id = '.$this->game['match_id']);
	Yii::$app->db->createCommand($sql)->execute();	
}
private static function getTowersState($num){
	$status = decbin($num);
	$len = strlen($status);
	if($len < 11){
		for ($i = 0; $i < 11 - $len; $i++) { 
			$status = '0'.$status;
		}
	}
	return (string)$status;
}
private static function getRaxState($num){
	$status = decbin($num);
	$len = strlen($status);
	if($len < 6){
		for ($i = 0; $i < 6 - $len; $i++) { 
			$status = '0'.$status;
		}
	}
	return (string)$status;
}
public static function getTowerStatus($status){
	if($status == 0){
		return 'inactive';
	}
	return '';
}
public static $dire_towers = ['dire-ancient-2', 'dire-ancient-1', 'dire-bot-3', 'dire-bot-2', 'dire-bot-1', 'dire-mid-3', 'dire-mid-2', 'dire-mid-1', 'dire-top-3', 'dire-top-2', 'dire-top-1'];

public static $radiant_towers = ['radiant-ancient-2', 'radiant-ancient-1', 'radiant-bot-3', 'radiant-bot-2', 'radiant-bot-1', 
'radiant-mid-3', 'radiant-mid-2', 'radiant-mid-1', 'radiant-top-3', 'radiant-top-2', 'radiant-top-1'];

public static $radiant_rax = ['radiant-bot-rax-1', 'radiant-bot-rax-2', 'radiant-mid-rax-1', 'radiant-mid-rax-2', 'radiant-top-rax-1', 'radiant-top-rax-2'];
public static $dire_rax = ['dire-bot-rax-1', 'dire-bot-rax-2', 'dire-mid-rax-1', 'dire-mid-rax-2', 'dire-top-rax-1', 'dire-top-rax-2'];

public static function GetGame($match_id){
	$data = [];

	$data['dire'] = [];
	$data['radiant'] = [];

	$data['dire']['picks'] = [];
	$data['dire']['bans'] = [];
	$data['radiant']['picks'] = [];
	$data['radiant']['bans'] = [];


	$data['game'] = Match::find()->from('dota_games')->where(['match_id' => $match_id])->asArray()->one();

	$pb = Match::find()->from('dota_picks_bans')->where(['match_id' => $match_id])->asArray()->all();

	foreach ($pb as $value) {
		if($value['team'] == self::RADIANT_CODE){
			if($value['action_type'] == self::PICK_CODE){
				array_push($data['radiant']['picks'], $value['hero_id']);
			}else{
				array_push($data['radiant']['bans'], $value['hero_id']);
			}
		}else{
			if($value['action_type'] == self::PICK_CODE){
				array_push($data['dire']['picks'], $value['hero_id']);
			}else{
				array_push($data['dire']['bans'], $value['hero_id']);
			}
		}
	}
	$players = Match::find()->select('*')->from('dota_players')->where(['match_id' => $match_id])->orderBy('level DESC, team')->asArray()->all();
	//$items = Match::find()->select('p.*, i.*')->from('dota_players_items p')->leftJoin('dota_items i', 'i.id = p.item_id')->where(['p.match_id' => $match_id])->orderBy('p.item_id DESC')->asArray()->all();

	$items = Match::find()->select('*')->from('dota_players_items')->where(['match_id' => $match_id])->orderBy('item_id DESC')->asArray()->all();

	$p = ['dire' => [], 'radiant' => []];
	for ($i = 0; $i < count($players); $i++) { 
		$class = $players[$i]['team'] == self::DIRE_CODE ? 'dire' : 'radiant';
		$h = count($p[$class]);
		$p[$class][$h] = $players[$i];
		$p[$class][$h]['items'] = [];
		for($g = 0; $g < count($items); $g++){
			if($players[$i]['account_id'] == $items[$g]['account_id']){
				array_push($p[$class][$h]['items'], $items[$g]);
			}
		}
	}

	$data['players'] = $p;

	$graph = Match::find()->from('dota_game_graph')->where(['match_id' => $match_id])->orderBy('time')->asArray()->all();
	$data['shedule'] = [];
	for ($i = 0; $i < 5; $i++) {
		$data['shedule'][$i] = [];
	}

	foreach ($graph as $shedule) {
		array_push($data['shedule'][(int)$shedule['type']], ['y' => (int)$shedule['data'], 'x' => (int)$shedule['game_time']]);
	}
	$dts = self::getTowersState($data['game']['dire_tower']);
	for ($i = 0; $i < 11; $i++) { 
		$data['tower'][self::$dire_towers[$i]] = (int)$dts[$i];
	}
	$rts = self::getTowersState($data['game']['radiant_tower']);
	for ($i = 0; $i < 11; $i++) { 
		$data['tower'][self::$radiant_towers[$i]] = (int)$rts[$i];
	}

	$drs = self::getRaxState($data['game']['dire_rax']);
	//var_dump(self::getRaxState(0));
	for ($i = 0; $i < 6; $i++) { 
		$data['rax'][self::$dire_rax[$i]] = (int)$drs[$i];
	}
	$rrs = self::getRaxState($data['game']['radiant_rax']);
	for ($i = 0; $i < 6; $i++) { 
		$data['rax'][self::$radiant_rax[$i]] = (int)$rrs[$i];
	}
	return $data;

}
public static function find($radiant, $dire, $team1, $team2){
		$radiant = (int)$radiant;
		$dire = (int)$dire;
		$team1 = (int)$team1;
		$team2 = (int)$team2;
		// $team1_s = false;
		// $team2_s = false;

		// if($radiant == (int)$team1 || $dire == (int)$team1){
		// 	$team1_s = true;
		// }
		// if($radiant == (int)$team2 || $dire == (int)$team2){
		// 	$team2_s = true;
		// }
		// if($team1_s && $team2_s && !empty($team1) && !empty($team2)){
		// 	return true;
		// }else if(!empty($team1) && $team1_s){
		// 	return true;
		// }else if(!empty($team2) && $team2_s){
		// 	return true;
		// }else{
		// 	return false;
		// }
		if($radiant == $team1 || $radiant == $team2){
			return true;
		}
		if($dire == $team1 || $dire == $team2){
			return true;
		}

		return false;
	}
public static function findStream($match_id){
	    $ch = curl_init('http://www.trackdota.com/data/game/'.$match_id.'/core.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
       	curl_setopt($ch, CURLOPT_USERAGENT, "24cyber.ru info-bot");
        curl_setopt ($ch, CURLOPT_PROXY, "45.55.233.201:1228");
        curl_setopt($ch,CURLOPT_ENCODING , "gzip");
    // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    // curl_setopt($ch,CURLOPT_NOBODY,true);
    // curl_setopt($ch,CURLOPT_HEADER,true);
        $data = curl_exec($ch); 
        curl_close($ch);

        $data = json_decode($data);
        $streams = [];
        foreach ($data->streams as $stream) {
        	if($stream->provider == 'twitch'){
        		$sort = 7;
        		if(strtolower($stream->language) == 'ru'){
        			$sort = 6;
        		}
        		array_push($streams, ['channel' => $stream->channel, 'country' => $stream->language, 'sort' => $sort]);
        	}
        }
        return $streams;
}



}


?>