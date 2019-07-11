<?php
namespace app\models;
use yii\db\Query;
use Yii;
use app\models\LoadData;
use app\models\WikiParse;

class TeamData extends LoadData{

	private $team;
	private $game_string;

	private $wikitext;


	private function parseOverview($page){
		$page = preg_replace('/<!--.+?-->/s', '', $page);
		$page = \phpQuery::newDocumentHTML($page)->find('#mw-content-text');
		$page->find('h2 span')->eq(0)->parent('h2')->prevAll()->remove();
		$page->find('.thumb')->remove();

		$tabs = $page->find('.tabs-dynamic');
		for ($i = 0; $i < count($tabs); $i++) {
			$tab = $tabs->eq($i)->find('.nav-tabs')->find('li');
			$content = $tabs->eq($i)->find('.tabs-content div[class^=content]');
			for ($g = 0; $g < count($tab); $g++) { 
				$class = $tab->eq($g)->attr('class');
				if(preg_match('/tab/', $class)){
					$text = $tab->eq($g)->text();
					$content->eq($g)->prepend('<h6>'.$text.'</h6>');
				}
			}
			$tabs->eq($i)->find('.nav-tabs')->remove();
		}
		$page->find('sup')->remove();
		$imga = $page->find('a img');
		for ($i = 0; $i < count($imga); $i++) { 
			$img = $imga->eq($i)->parent('a')->html();
			$imga->eq($i)->parent('a')->replaceWith($img);
		}
		$h = $page->find('h2');
		for ($i = 0; $i < count($h); $i++) { 
			if(preg_match('/highlight videos|references|achievements|gallery|awards|links|interviews|highlights/is', $h->eq($i)->text())){
				$next = $h->eq($i)->nextAll();
				$h->eq($i)->remove();
				for ($g = 0; $g < count($next); $g++) { 
					if(!$next->eq($g)->is('h2')){
						$next->eq($g)->remove();
					}else{
						break;
					}
				}
			}
		}
		$img = $page->find('img');
		for ($i = 0; $i < count($img); $i++) { 
			$link = $img->eq($i)->attr('src');
			$n = LoadData::saveLogo($link);
			$img->eq($i)->attr('src', $n);
		}
		$at = $page->find('table')->find('a');
		for ($i = 0; $i < count($at); $i++) { 
			$text = $at->eq($i)->text();
			$at->eq($i)->replaceWith($text);
		}
		$a = $page->find('a');
		$buffer_m = [];
		$counter_m = 0;
		for ($i = 0; $i < count($a); $i++) { 
			$text = $a->eq($i)->text();
			$buffer_m['coram-'.$counter_m] = $text;
			$a->eq($i)->replaceWith('<!--coram-'.$counter_m.'-->');
			$counter_m++;
		}
		$tables = $page->find('table');
		for ($i = 0; $i < count($tables); $i++) { 
			$text = $tables->eq($i)->html();

			$buffer_m['coram-'.$counter_m] = '<table>'.$text.'</table>';
			$tables->eq($i)->replaceWith('<!--coram-'.$counter_m.'-->');
			$counter_m++;


		}

		$html = preg_replace('/\n/', '', $page->html());
		$html = preg_replace('/;/', '', $html);
		
		$index = 0;
		$buffer = '';

		$counter = 0;

		
		// echo  strlen(trim($html)).'</br>';
		// $str = substr(trim($html), $counter, 10000);
		// echo strlen($str).'</br>';

		while($counter < strlen(trim($html))){
			$str = substr(trim($html), $counter, 10000);
			if(substr_count($str, '<!--') > substr_count($str, '-->')){
				$str = substr($str, 0, strripos($str, '<!--'));
			}else if(substr_count($str, '<') > substr_count($str, '>')){
				$str = substr($str, 0, strripos($str, '<'));
			}

			$counter += strlen($str);

			//echo $str;
			$buffer .= LoadData::translate($str);

		}
		$buffer = preg_replace_callback('/<!--(coram-\d+?)-->/', function($sub) use($buffer_m){
			return $buffer_m[$sub[1]];
		}, $buffer);

		$buffer = preg_replace('/\'/', "\'", $buffer);
		$blocks = preg_split("/<h2>(.+?)<\/h2>/s", $buffer, -1, PREG_SPLIT_DELIM_CAPTURE);
		$dat = [];
		for ($i = 1; $i < count($blocks); $i += 2) {
			$text = $blocks[$i + 1];
			if(!empty($text) && isset($text)){
				array_push($dat, ['name' => htmlspecialchars($blocks[$i]), 'text' => htmlspecialchars($text), 'game' => $this->game]);
			}
			
		}
		return $dat;


	}
	private function getTeamDetails($team){
		$teamDetails = ['earning' => 0];
		foreach ($team->data as $value) {
			$prop = $value->property;
			$item = $value->dataitem[0]->item;
			switch ($prop) {
				case 'Has_image':
					$teamDetails['logo'] = LoadData::getLogo($item, $this->game_string);
					break;
				case 'Has_location':
					$teamDetails['location1'] = $item;
					break;
				case 'Has_location2':
					$teamDetails['location2'] = $item;
					break;
				case 'Has_name':
					$teamDetails['name'] = $item;
					break;
				case 'Was_created':
					$teamDetails['created'] = preg_replace('/\[\[.+?\]\]/s', '', $item);
					$teamDetails['created'] = preg_replace('/<.+?>/s', '', $teamDetails['created']);
					break;
				case 'Has_captain':
					$teamDetails['captain'] = preg_replace('/\'/', '', $item);
					$teamDetails['captain'] = preg_replace('/^\[\[.+?\]\]/s', '', $teamDetails['captain']);
					$teamDetails['captain'] = preg_replace('/\[|\]/', '', $teamDetails['captain']);
					break;
				case 'Has_earnings':
					$teamDetails['earning'] = preg_replace('/[^0-9\.]+/', "", $item);
					break;
				default:
					break;
			}
			
		}
		$teamDetails['name'] = empty($teamDetails['name']) ? $teamDetails[$this->team] : $teamDetails['name'];

		if(isset($teamDetails['location2']) && !empty($teamDetails['location2'])){
           		$teamDetails['flag'] = $teamDetails['location2'];

        }else if(isset($teamDetails['location1']) && !empty($teamDetails['location1'])){
                $teamDetails['flag'] = $teamDetails['location1'];
        }else{
                $teamDetails['flag'] = null;
        }
        unset($teamDetails['location2']);
        unset($teamDetails['location1']);

        $teamDetails['team_id'] = $this->team_id;
        $teamDetails['game'] = $this->game;
        $teamDetails['ix'] = $this->team_id.'-'.$this->game;

        preg_match('/dotabuff=(\d+?)\n/', $this->wikitext, $m);
		$teamDetails['dotabuff'] = isset($m[1]) ? $m[1] : null;
		if(!empty($teamDetails['name'])){
			return $teamDetails;
		}
		return false;
		
	}
	// private function save($data){
	// 	$data['ix'] = $this->team_id.'-'.$this->game;
	// 	$data['team_id'] = $this->team_id;
	// 	$data['game'] = $this->game;
	// 	return LoadData::insertDuplicate('teams', $data);
	// }
	// private function saveAbout($data){
	// 	$sql = '';
	// 	foreach ($data as $value) {
	// 		$value['team_id'] = $this->team_id;
	// 		$value['game'] = $this->game;
	// 		$sql .= LoadData::insertDuplicate('about_blocks', $value);
	// 	}
	// 	return $sql;
	// }


	private function getPlayers(){
		$players = [];
		$w = preg_replace('/\n/', '', $this->wikitext);
		preg_match('/\{\{ActiveSquad\|.+?\}\}\}\}/x', $w, $m);
		$squad = preg_replace('/<ref.+?>/s', '', $m[0]);
		//print_r($squad);
		preg_match_all('/\{\{SquadPlayer.+?\}\}/x', $squad, $player);
		//print_r($player[0]);
		foreach ($player[0] as $value) {
				preg_match('/player=(.+?)\|/x', $value, $id);
				if(!preg_match('/<!--.+?-->/s', trim($id[1]))){
					array_push($players, preg_replace('/\s/', '_', trim($id[1])));
				}
		}
		return $players;
	}
	private function getPlayerInfo($data){
		//$player = ['earning' => 0, 'image' => 0, 'role' => 0, 'age' => 0, 'name' => 0, 'country' => 0];
		$player = [];
		foreach ($data->data as $value) {
                $prop = $value->property;
			    $item = $value->dataitem[0]->item;
			  switch ($prop) {
				case 'Has_earnings':
					$player['earning'] = $item;
					break;
				case 'Has_image':
					$player['image'] = LoadData::getLogo($item, $this->game_string);
					break;
				case 'Has_role':
					$player['role'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_age':
					$player['age'] = $item;
					break;
				case 'Has_name':
					$player['name'] = $item;
					break;
				case 'Has_nationality':
					$player['country'] = $item;
				default:
					break;
			}	
		}
		$player['team_id'] = $this->team_id;
		$player['game'] = $this->game;
		
		return $player;
	}
	private function playerDetails($player_id){
		$player = LoadData::get('http://wiki.teamliquid.net/'.$this->game_string.'/api.php?action=browsebysubject&subject='.$player_id.'&format=json');
		$player = json_decode($player)->query;

		$player_html = file_get_contents('http://wiki.teamliquid.net/'.$this->game_string.'/'.$player_id);

		//echo $player_html;
		$sql = 'DELETE FROM teams_players_about WHERE id = "'.$player_id.'" AND game = "'.$this->game.'"; 
		DELETE FROM players WHERE player_id = "'.$player_id.'" AND game = "'.$this->game.'"; ';

		$blocks = $this->parseOverview($player_html);
		foreach ($blocks as $value) {
			$value['id'] = $player_id;
			$sql .= LoadData::insert('teams_players_about', $value);
		}

		preg_match('/"http:\/\/www\.dotabuff\.com\/esports\/players\/(.+?)"/', $player_html, $m);


		$playerData = $this->getPlayerInfo($player);

		$playerData['player_id'] = $player_id;
		$playerData['dotabuff'] = $m[1];
		//print_r($playerData);

		//print_r($blocks);
		//print_r($playerData);
		$sql .= LoadData::insert('players', $playerData);

		return $sql;
	}
	private function load(){
		$wikitext = LoadData::get('http://wiki.teamliquid.net/'.$this->game_string.'/api.php?action=parse&page='.$this->team_id.'&redirects&prop=wikitext&format=json');
		$html = file_get_contents('http://wiki.teamliquid.net/'.$this->game_string.'/'.$this->team_id);
		$team = LoadData::get('http://wiki.teamliquid.net/'.$this->game_string.'/api.php?action=browsebysubject&subject='.$this->team_id.'&format=json');
		
		
		$this->team = json_decode($team)->query;
		$this->wikitext = json_decode($wikitext)->parse->wikitext->{'*'};
		
		$teamDetails = $this->getTeamDetails($this->team);
		
		if($teamDetails){
		$over = $this->parseOverview($html);
		
		
		 $sql = 'DELETE FROM teams_players_about WHERE id = "'.$this->team_id.'" AND game = "'.$this->game.'"; ';

		$players = $this->getPlayers();

		print_r($players);

		foreach ($players as $player) {
			$sql .= $this->playerDetails($player);
		}
		foreach ($over as $value) {
			$value['id'] = $this->team_id;
			$sql .= LoadData::insert('teams_players_about', $value);

		}
		
		
		
		$sql .= LoadData::insertDuplicate('teams', $teamDetails);
		// // $sql .= $this->save($teamDetails);
		// // $sql .= $this->saveAbout($over);
		Yii::$app->db->createCommand($sql)->execute();
	}
	}

}


?>