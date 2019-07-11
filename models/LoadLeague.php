<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\DotaHelper;
use app\models\Match;
use yii\helpers\Url;
use \Exception;


class LoadLeague{

	public $league;
	public $matches;
	public $teams;
	public $quals;
	private $game;
	private $league_id;
	public $link;


	private $wikitext;
	private $league_data;

	function __construct($league_id, $game){
		if(!empty($league_id)){
			$this->league_id = $league_id;
			$this->game = $game;
			$this->link = DotaHelper::tlBuilder($this->league_id, $this->game);

		}else{
			throw new Exception('Пустые данные для загрузки');
		}

		//$this->load();
	}
	private function getTeams($object){
		$teams = [];
       foreach ($object as $value) {
       	   if(preg_match('/ranking/', $value['subject'])){
       	   	$team = [];
       	   	   foreach ($value['data'] as $params) {
       	   	   		$prop = $params['property'];
			        $item = $params['dataitem'][0]['item'];
       	   	   	switch ($prop) {
				case 'Has_placement':
					$team['place'] = $item;
					break;
				case 'Has_prizemoney':
					$team['prize'] = $item;
					break;
				case 'Has_team_page':
					$team['team'] = preg_replace('/#0#/', '', $item);
					break;
				default:
					break;
			}
       	   	   }
       	   	   if(!empty($team['team'])){
       	   	   		if(DotaHelper::check($team['team'])){
       	   	   			array_push($teams, $team);
       	   	   		}
       	   	}
       	   	   
       	   }

       }
       return $teams;
	}
	private function getQuals(){
		$r = [];
		$kvals = [];
		preg_match_all('/qualifier=\[\[(.+?)\]\]/', $this->wikitext, $all);
		foreach ($all[1] as $value) {
			$kval = [];
			preg_match('/^(.+?)\|(.+?)$/', $value, $m);
			$kval['link'] = DotaHelper::tlBuilder(preg_replace('/\s/', '_', $m[1]), $this->game);
			$kval['name'] = $m[2];
			if(!isset($r[$kval['link']])){
				$r[$kval['link']] = true;
				array_push($kvals, $kval);
			}

		}

		return $kvals;
	}

	private function leagueInfo($league_data){
		$leagueInfo = [];
		$leagueInfo['id'] = $this->league_id;
		$leagueInfo['event'] = 0;
		$leagueInfo['date_start'] = date('Y-m-d');
		foreach ($league_data as $props) {
			$prop = $props['property'];
			$item = $props['dataitem'][0]['item'];
			switch ($prop) {
				case 'Has_date':
					$leagueInfo['date_start'] = DotaHelper::apiLeagueDateParser($item);
					break;
				case 'Has_end_date':
					$leagueInfo['date_end'] = DotaHelper::apiLeagueDateParser($item);
					break;
				case 'Has_image':
					$leagueInfo['image'] = DotaHelper::getImage(DotaHelper::tlLogoParser($item, $this->game));
					break;
				case 'Has_id':
					$leagueInfo['id'] = $item;
					break;
				case 'Has_name':
					$leagueInfo['name'] = $item;
					break;
				case 'Has_prizepool_in_usd':
					$leagueInfo['prizepool'] = preg_replace('/[^0-9]+/', "", $item);
					break;
				default:
					break;
			}
			
		}
		if(!empty($leagueInfo['id'])){
			if(empty($leagueInfo['name'])){
				$leagueInfo['name'] = $leagueInfo['id'];
			}
			$leagueInfo['game'] = $this->game;
			$leagueInfo['league_id'] = $this->league_id;
			return $leagueInfo;
		}else return null;
	}
	private function getMatches($object){
		$matches = [];
       	foreach ($object as $value) {
       	   if(preg_match('/_vs_/', $value['subject'])){
       	   	$match = [];
       	   	$match['series'] = 1;
       	   	   foreach ($value['data'] as $params) {
       	   	   		$prop = $params['property'];
			        $item = $params['dataitem'][0]['item'];
       	   	   	switch ($prop) {
				case 'Has_map_date':
					$match['date'] = DotaHelper::apiMatchDateParser($item);
					break;
				case 'Has_team_left':
					$match['team1'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_team_left_score':
					$match['score_left'] = $item;
					break;
				case 'Has_team_right':
					$match['team2'] = preg_replace('/#0#/', '', $item);
					break;
				case 'Has_team_right_score':
					$match['score_right'] = $item;
					break;
				case 'Is_finished':
					$match['over'] = $item == 't' ? 1 : 0;
					break;
				case 'Is_map_number':
					$leagueInfo['series'] = $item;
				default:
					break;
			}
			}	
       	   	   	if(isset($match['team1']) && isset($match['team2']) && !empty($match['date'])){
       	   	   		if(DotaHelper::check($match['team1']) && DotaHelper::check($match['team2'])){
       	   	   			array_push($matches, $match);
       	   	   		}
       	   	   	}
       	   }

       }
       return $matches;
	}
	public function getData(){
		$array = [
			'league' => $this->league,
			'matches' => $this->matches,
			'teams' => $this->teams,
			'quals' => $this->quals

		];
		
		return json_encode($array);
	}
	private function gMatches(){
		if(!empty($this->page)){
		$dom = \phpQuery::newDocumentHTML($this->page);

		$games = $dom->find('.bracket-popup');

		for($i = 0; $i < count($games); $i++){
			$game = [];
			$g = $games->eq($i);

			$team1 = $g->find('.bracket-popup-header .bracket-popup-header-left .team-template-text a')->attr('href');
			$team2 = $g->find('.bracket-popup-header .bracket-popup-header-right .team-template-text a')->attr('href');

			if(preg_match('/index\.php/', $team1)){
				$game['team1'] = preg_replace('/\s/', '_', $g->find('.bracket-popup-header .bracket-popup-header-left .team-template-text a')->text());
			}else{
				$game['team1'] = preg_replace('/^\/.+?\/(.+?)$/', '$1', $team1);
			}
			
			if(preg_match('/index\.php/', $team2)){
				$game['team2'] = preg_replace('/\s/', '_', $g->find('.bracket-popup-header .bracket-popup-header-right .team-template-text a')->text());
			}else{
				$game['team2'] = preg_replace('/^\/.+?\/(.+?)$/', '$1', $team2);
			}
			
			$time = $g->find('.bracket-popup-body-time')->html();
	     	$utc = $g->find('.bracket-popup-body-time abbr')->attr('data-tz');
	     	preg_match('/(.+?)<abbr/', $time, $timeS);
	     	if(!empty($timeS)){
	         	$time = preg_replace('/<.+>/', '', $timeS[1]);
	         	$time = preg_replace('/-/', "", $time);
	         	$time = $time.' GMT'.$utc;
	        	$date = strtotime($time);

	        	$game['date'] = $date;

	        }

			$s = $g->find('.bracket-popup-body-match');
			$game['series'] = count($s);

			//print_r($game);
			if(!empty($game['team1']) && !empty($game['team2'])){
				foreach ($this->matches as $key => $match) {
					if(stristr($match['team1'], $game['team1']) !== FALSE && stristr($match['team2'], $game['team2']) !== FALSE && strtotime($match['date']) == $game['date']){
						$this->matches[$key]['series'] = $game['series'];
						break;
					}
				}
			}
		}
	}
	}
	private function parse(){
		$this->league = $this->leagueInfo($this->league_data['data']);
		if(!empty($this->league)){
			$this->matches = $this->getMatches($this->league_data['sobj']);
			$this->gMatches();
			$this->teams = $this->getTeams($this->league_data['sobj']);
			if($this->league['event'] == 0){
				$this->quals = $this->getQuals();
			}else{
				$this->quals = [];
			}
			return $this->getData();
		}else throw new Exception("Ошибка при парсинге данных");
		
	}
	public function load(){
		$api_call = DotaHelper::httpGet(DotaHelper::tlBuilder('api.php', $this->game), ['action' => 'browsebysubject', 'format' => 'json', 'subject' => $this->league_id, 'maxlag' => 1], true);
		if(!empty($api_call)){
			$league_data = json_decode($api_call, true);
			
			if(json_last_error() == JSON_ERROR_NONE){
				if(!empty($league_data['query']['data'])){
					$this->league_data = $league_data['query'];
					$wikitext = DotaHelper::httpGet(DotaHelper::tlBuilder('api.php', $this->game), 
						['action' => 'parse', 
						'format' => 'json',
						'prop' => 'wikitext',
						'redirects' => '', 
						'page' => $this->league_id, 'maxlag' => 1], true);
					if(!empty($wikitext)){
						$wikitext_data = json_decode($wikitext, true);
						if(json_last_error() == JSON_ERROR_NONE){
							if(!isset($wikitext_data['error'])){
								$this->wikitext = $wikitext_data['parse']['wikitext']['*'];
								$this->page = DotaHelper::httpGet(DotaHelper::tlBuilder($this->league_id, $this->game));
								return $this->parse();
							}else throw new Exception("Ошибка при загрузке викиразметки");

						}else throw new Exception("Ошибка при загрузке викиразметки");

					}else throw new Exception("Ошибка при загрузке викиразметки");

				}else throw new Exception("Ошибка при загрузке данных о лиге");

			}else throw new Exception("Ошибка при загрузке данных о лиге");

		}else throw new Exception("Ошибка при загрузке данных о лиге");

	}



}
?>