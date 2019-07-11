<?php
namespace app\models;
use Yii;
use yii\db\Query;

class CsgoHltv{
	public static function getLiveMatches(){
		$matches = [];
		$data = file_get_contents('http://www.hltv.org/matches/');
		$dom = \phpQuery::newDocumentHTML($data);

		$ms = $dom->find('.live-matches .live-match');
		for($i = 0; $i < count($ms); $i++){
			$m = $ms->eq($i);
				$match = [];

				if(preg_match('/\/matches\/(\d+?)\//', $m->find('a')->attr('href'), $id)){
				$match['id'] = $id[1];
				$match['url'] = 'http://www.hltv.org'.$m->find('a')->attr('href');

				$match['details'] = self::getMatch($match['url']);
				array_push($matches, $match);
			}
		}
		return $matches;
	}
	public static function getStream($id){
		$data = DotaHelper::httpGet('http://www.hltv.org/?pageid=286&streamid='.$id);
		$dom = \phpQuery::newDocumentHTML($data);

		$s = $dom->find('iframe')->attr('src');
		
		if(preg_match('/^http:\/\/player\.twitch\.tv\/\?channel=(.+?)$/', $s, $d)){
			return $d[1];
		}
		return null;
	}
	public static function getMatch($url){
		$data = file_get_contents($url);
		$dom = \phpQuery::newDocumentHTML($data);

		$match = [];
		preg_match('/Best of (\d)/', $data, $s);
		$match['map'] = $s[1];

		$status = strtolower($dom->find('.timeAndEvent .countdown')->eq(0)->text());
		if($status == 'match over'){
			$match['over'] = 1;
		}else{
			$match['over'] = 0;
		}
		$match['team1'] = preg_replace('/.+\/(\d+?)\/.+$/', '$1', $dom->find('.team1-gradient a')->attr('href'));
		$match['team2'] = preg_replace('/.+\/(\d+?)\/.+$/', '$1', $dom->find('.team2-gradient a')->attr('href'));

		// $games = $dom->find('span[id^=map_link_]');
		// $match['games'] = [];
		// for($i = 0; $i < count($games); $i++){
		// 	array_push($match['games'], preg_replace('/^map_link_(\d+?)$/', '$1', $games->eq($i)->attr('id')));
		// }
		$streams = $dom->find('.streams .stream-box');
		$match['streams'] = [];
		for($i = 0; $i < count($streams); $i++){
			$s = $streams->eq($i);
			if(preg_match('/player\.twitch\.tv\/\?channel=(.+?)$/', $s->attr('data-stream-embed'), $m)){
				$stream = [];
				$stream['channel'] = $m[1];
				preg_match('/\/([^\/]+?)\.gif$/', $s->find('img')->attr('src'), $flag);
				$stream['flag'] = strtolower($flag[1]);
				array_push($match['streams'], $stream);
			}

		}
		$event_id = preg_replace('/\/.+\//', '', $dom->find('.event a')->attr('href'));
		$match_id = str_replace('-'.$event_id, '', preg_replace('/.+\//', '', $url));
		$links = $dom->find('.matchstats .stats-menu-link .dynamic-map-name-full');
		$match['games'] = [];
		for ($i = 0; $i < count($links); $i++) { 
			$link = $links->eq($i);
			if(preg_match('/(\d+?)$/', $link->attr('id'), $m)){
				$game = [];
				$game['id'] = $m[1];
				$game['link'] = 'https://www.hltv.org/stats/matches/mapstatsid/'.$game['id'].'/'.$match_id;
				array_push($match['games'], $game);
			}
		}
		
		return $match;
	}
	public static function getMatchDetails($url){
		$data = file_get_contents($url);
		$dom = \phpQuery::newDocumentHTML($data);

		$match = [];

		$match['team1']['name'] = $dom->find('.team-left a')->text();
		$match['team1']['id'] = preg_replace('/.+\/(\d+?)\/.+?$/', '$1', $dom->find('.team-left a')->attr('href'));
		$match['score_left'] = $dom->find('.team-left div')->text();

		$match['team2']['name'] = $dom->find('.team-right a')->text();
		$match['team2']['id'] = preg_replace('/.+\/(\d+?)\/.+?$/', '$1', $dom->find('.team-right a')->attr('href'));
		$match['score_right'] = $dom->find('.team-right div')->text();

		$match['history']['team1']['first_side'] = [];
		$match['history']['team1']['second_side'] = [];
		
		$match['history']['team2']['first_side'] = [];
		$match['history']['team2']['second_side'] = [];

		$history = $dom->find('.round-history-con');

		$t1_s1 = $history->find('.round-history-team-row')->eq(0)->find('.round-history-half')->eq(0)->find('img');

		for($i = 0; $i < count($t1_s1); $i++){
			array_push($match['history']['team1']['first_side'], $t1_s1->eq($i)->attr('src'));
		}
		$t1_s2 = $history->find('.round-history-team-row')->eq(0)->find('.round-history-half')->eq(1)->find('img');

		for($i = 0; $i < count($t1_s2); $i++){
			array_push($match['history']['team2']['first_side'], $t1_s2->eq($i)->attr('src'));
		}

		$t2_s1 = $history->find('.round-history-team-row')->eq(1)->find('.round-history-half')->eq(0)->find('img');

		for($i = 0; $i < count($t2_s1); $i++){
			array_push($match['history']['team1']['second_side'], $t2_s1->eq($i)->attr('src'));
		}
		$t2_s2 = $history->find('.round-history-team-row')->eq(1)->find('.round-history-half')->eq(1)->find('img');

		for($i = 0; $i < count($t2_s2); $i++){
			array_push($match['history']['team2']['second_side'], $t2_s2->eq($i)->attr('src'));
		}


		$match['players'] = [];

		$players = $dom->find('.stats-table')->eq(0)->find('tr');

		for($i = 1; $i < count($players); $i++){
			$player = [];
			$player['name'] = $players->eq($i)->find('.st-player a')->text();
			$player['team'] = 0;

			$players->eq($i)->find('.st-kills span')->remove();
			$player['k'] = $players->eq($i)->find('.st-kills')->text();
			$player['d'] = $players->eq($i)->find('.st-deaths')->text();
			$player['a'] = $players->eq($i)->find('.st-assists')->text();
			$player['r'] = $players->eq($i)->find('.st-rating')->text();

			array_push($match['players'], $player);
		}
		$players = $dom->find('.stats-table')->eq(1)->find('tr');

		for($i = 1; $i < count($players); $i++){
			$player = [];
			$player['name'] = $players->eq($i)->find('.st-player a')->text();
			$player['team'] = 1;
			$players->eq($i)->find('.st-kills span')->remove();
			$player['k'] = $players->eq($i)->find('.st-kills')->text();
			$player['d'] = $players->eq($i)->find('.st-deaths')->text();
			$player['a'] = $players->eq($i)->find('.st-assists')->text();
			$player['r'] = $players->eq($i)->find('.st-rating')->text();

			array_push($match['players'], $player);
		}
		return $match;
		//print_r($match);
	}
	public static function loadMatch($match_id){
		$match = [];
		$game = (new Query())->select('*')->from('hltv_matches')->where(['game_id' => $match_id])->one();
		$players = (new Query())->select('*')->from('hltv_players')->where(['game_id' => $match_id])->all();
		
		$match['game'] = $game;
		$match['players'] = $players;

		$match['game']['history'] = json_decode($match['game']['history'], true);

		return $match;
	}
	public static function getVods($url){
		$data = DotaHelper::httpGet($url);
		$dom = \phpQuery::newDocumentHTML($data);

		$streams = $dom->find('.streams .stream-box');
		$vods = [];
		for($i = 0; $i < count($streams); $i++){
			$s = $streams->eq($i);
			$h = $s->attr('data-stream-embed');
			if(!empty($h)){
				if(preg_match('/^http/', $h)){
					array_push($vods, $h);
				}
			}
			
		}

		return $vods;

	}
}


?>