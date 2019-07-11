<?php
namespace app\models;
use app\models\LoadData;
use Yii;

class ParseBracket{
	private $league;
	private $game;
	private $table = null;
	private $pattern = '/^\/.+?\/(.+?)$/';

	public function setLeague($league){
		$this->league = $league;
	}	
	public function setGame($game){
		$this->game = $game;
	}


	private function getHeroes($row){
		$map = ['left' => [], 'right' => []];
		for ($i = 0; $i < 5; $i++) { 
			$hero1_url = $row->find('.left a')->eq($i)->attr('href');
			$hero2_url = $row->find('.right a')->eq($i)->attr('href');

			preg_match($this->pattern, $hero1_url, $hero1_name);
			preg_match($this->pattern, $hero2_url, $hero2_name);

			array_push($map['left'], isset($hero1_name[1]) ? $hero1_name[1] : '');
			array_push($map['right'], isset($hero2_name[1]) ? $hero2_name[1] : '');


		}
		$win1 = $row->find('.left .check img')->attr('src');
		$win2 = $row->find('.right .check img')->attr('src');

		$map['left']['win'] = preg_match('/GreenCheck\.png/', $win1) ? 1 : 0;
		$map['right']['win'] = preg_match('/GreenCheck\.png/', $win2) ? 1 : 0;
		return $map;
	}

	private function getDota2($match){
		if(count($match) > 0){
			$game = [];

			$row = $match->find('.bracket-popup-body-match');
			for ($i = 0; $i < count($row); $i++) { 
				$heroes = $this->getHeroes($row->eq($i));
				array_push($game, $heroes);
			}
			$ids = $match->find('.bracket-popup-footer a');
			$c = 0;
			for ($i = 0; $i < count($ids); $i++) { 
				if(preg_match('/dotabuff\.com\/matches\/(.+?)$/', $ids->eq($i)->attr('href'), $id)){
					$game[$c]['id'] = empty($id[1]) ? null : $id[1];
					$c++;
				}
			}


			return $game;


		}else{
			return false;
		}
	}
	private function getCsgo($match){
		if(count($match) > 0){
			$game = [];

			$row = $match->find('.bracket-popup-body-match');
			
			for ($i = 0; $i < count($row); $i++) { 
				$score = [];
				$score['left'] = $row->eq($i)->find('div[style="float:left;margin-left:10px;"]')->eq(0)->find('td')->eq(0)->text();
				$score['right'] = $row->eq($i)->find('div[style="float:right;margin-right:10px;"]')->eq(0)->find('td')->eq(0)->text();
				$score['map'] = $row->eq($i)->find('div[style="padding-bottom:5px;padding-top:5px;"]')->eq(0)->text();
				if(!empty($score['left']) && !empty($score['right']) && !empty($score['map'])){
					array_push($game, $score);
				}
				
			}
			return $game;


		}else{
			return false;
		}
	}
	private function getIndex($dom){
		if(count($dom->find('.bracket-popup')) == 1){
			$team1_url = $dom->find('.bracket-popup-header .bracket-popup-header-left a')->attr('href');
			$team2_url = $dom->find('.bracket-popup-header .bracket-popup-header-right a')->attr('href');
			preg_match($this->pattern, $team1_url, $team1_name);
			preg_match($this->pattern, $team2_url, $team2_name);

			$time = $dom->find('.bracket-popup-body-time')->html();
         	$utc = $dom->find('.bracket-popup-body-time abbr')->attr('data-tz');
         	preg_match('/(.+?)<abbr/', $time, $timeS);
         	if(!empty($timeS)){
	         	$time = preg_replace('/<.+>/', '', $timeS[1]);
	         	$time = preg_replace('/-/', "", $time);
	         	$time = $time.' GMT'.$utc;
	        	$date = strtotime($time);
	        	$date = date("Y-m-d", $date);   

	        	if(!empty($team2_name[1]) && !empty($team1_name[1])){
        			$team1 = LoadData::team($team1_name[1]);
        			$team2 = LoadData::team($team2_name[1]);
        				if(!empty($date) && !empty($team1) && !empty($team2) && !empty($this->league_id)){
        					return $team1.'|'.$team2.'|'.$date.'|'.$this->league_id;
        				}
        		}      		
         	}



		}
		return false;
		
	}
	public function start($data){
		$dom = \phpQuery::newDocumentHTML($data);
       	$brackets = $dom->find('.bracket-wrapper');

       	$parseB = [];

       	for ($i = 0; $i < count($brackets); $i++) { 
       		//echo $brackets->eq($i)->html();
       		for ($g = 0; $g < count($brackets->eq($i)->find('.bracket-game')); $g++) {
       			$match = $brackets->eq($i)->find('.bracket-game')->eq($g);
       			$index = $this->getIndex($match);

    		$team = $brackets->eq($i)->find('.bracket-game')->eq($g)->find('[class^="bracket-cell"]');
				
				for ($j = 0; $j < count($team); $j++) { 
       				$tmp = $team->eq($j)->html();
       				$team->eq($j)->html('<a class="match-url" index="'.$index.'"></a>');
       				$team->eq($j)->find('.match-url')->html($tmp);
       			}
       		}
       		$brack = ['name' => preg_replace('/Bracket/', "", $brackets->eq($i)->siblings('h3')->eq(0)->text()), 'bracket' => preg_replace('/\'/', "\'", htmlspecialchars($brackets->eq($i)->html()))];
       		array_push($parseB, $brack);
       	}
       $this->data = $parseB;
	}
	private function getGroups($data){
		$dom = \phpQuery::newDocumentHTML($data);

		$groups = $dom->find('.grouptable');

		$this->groups = [];
		for ($i = 0; $i < count($groups); $i++) { 
			$g = $groups->eq($i);
			//echo $g->html();
			$group = [];

			$a = $g->find('a');

			for($s = 0; $s < count($a); $s++){
				$data = $a->eq($s)->html();
				$a->eq($s)->replaceWith($data);
			}
			$name = $g->parents('.template-box')->find('.mw-headline')->text();
			
			$g->parents('.template-box')->find('.mw-editsection')->remove();
			$g->parents('.template-box')->find('.matchlist')->remove();
			$g->parents('.template-box')->find('.mw-headline')->remove();

			$g->removeClass('table');
			$g->removeClass('table-bordered');
			$g->removeClass('table-striped');
			$g->find('tr')->eq(0)->remove();
			$g = $g->parents('.template-box')->html();
			$g = preg_replace('/style=".+?"/', '', $g);
			if(!empty($g)){
				$group['data'] = htmlspecialchars($g);
				$group['data'] = preg_replace('/\'/', "\'", $group['data']);
				$group['name'] = $name;
				array_push($this->groups, $group);
			}

		}

	}
	private function getTable($data){
		$dom = \phpQuery::newDocumentHTML($data);

		$table = $dom->find('.table-responsive .swisstable')->eq(0);

		$a = $table->find('a');

		for($i = 0; $i < count($a); $i++){
			$data = $a->eq($i)->html();
			$a->eq($i)->replaceWith($data);
		}

		$table->removeClass('table');
		$table->removeClass('table-bordered');
		$table->removeClass('table-striped');
		$table = $table->parent('.table-responsive')->html();


		if(!empty($table)){
			$this->table = htmlspecialchars($table);
			$this->table = preg_replace('/\'/', "\'", $this->table);
		}
	}
	function __construct($league, $game){
		$this->game = $game;
		$this->league_id = $league;

		$this->game_string = $this->game == 0 ? 'dota2' : 'counterstrike';

		$data = DotaHelper::httpGet(DotaHelper::tlBuilder($this->league_id, $this->game));
		$this->start($data);
		$this->getTable($data);



		$this->getGroups($data);

		//print_r($this->groups);
	}
	public function saveTable($league_id){
		if(!empty($this->table)){
			$sql = 'DELETE FROM league_table WHERE league_id = '.$league_id.'; ';
			$sql .= LoadData::insert('league_table', ['league_id' => $league_id, 'data' => $this->table]);
			return $sql;
		}
		return '';
	}
	public function saveGroups($league_id){
		if(!empty($this->groups)){
			$sql = 'DELETE FROM league_groups WHERE league_id = '.$league_id.'; ';
			foreach ($this->groups as $group) { 
				$sql .= LoadData::insert('league_groups', ['league_id' => $league_id, 'data' => $group['data'], 'name' => $group['name']]);
			}
			return $sql;
		}
		return '';
	}

	public function get(){
		return $this->data;
	}
}

?>