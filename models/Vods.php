<?php

namespace app\models;
use yii\db\Query;
use Yii;

class Vods
{
private $sql;

function __construct($league_id, $game){
	$this->league_id = $league_id;
	$this->game = $game;
}

public function getVods(){
	$page = DotaHelper::httpGet(DotaHelper::tlBuilder($this->league_id, $this->game));
	$dom = \phpQuery::newDocumentHTML($page);

	$vods = $dom->find('.bracket-popup');

	$matches = (new Query())->select('m.*')->from('matches m')->leftJoin('leagues l', 'l.id = m.league_id')->where(['l.league_id' => $this->league_id, 'm.active' => 1])->orderBy('m.date')->all();
	for($i = 0; $i < count($vods); $i++){
		$vod = [];
		$v = $vods->eq($i);

		$vod['team1'] = preg_replace('/^\/.+?\/(.+?)$/', '$1', $v->find('.bracket-popup-header .bracket-popup-header-left .team-template-text a')->attr('href'));
		$vod['team2'] = preg_replace('/^\/.+?\/(.+?)$/', '$1', $v->find('.bracket-popup-header .bracket-popup-header-right .team-template-text a')->attr('href'));

		$time = $v->find('.bracket-popup-body-time')->html();
     	$utc = $v->find('.bracket-popup-body-time abbr')->attr('data-tz');
     	preg_match('/(.+?)<abbr/', $time, $timeS);
     	if(!empty($timeS)){
         	$time = preg_replace('/<.+>/', '', $timeS[1]);
         	$time = preg_replace('/-/', "", $time);
         	$time = $time.' GMT'.$utc;
        	$date = strtotime($time);

        	$vod['date'] = $date;

        }

		$s = $v->find('.bracket-popup-footer a');
		$vod['videos'] = [];

		for($g = 0; $g < count($s); $g++){
			if(preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=(.+?)$/', $s->eq($g)->attr('href'), $link)){
				array_push($vod['videos'], $link[1]);
			}
		}
		foreach ($matches as $match) {
			if($match['team1'] == $vod['team1'] && $match['team2'] == $vod['team2'] && strtotime($match['date']) == $vod['date']){
				$vod['id'] = $match['id'];
				foreach ($vod['videos'] as $video) {
					$this->sql .= LoadData::insert('vods', ['match_id' => $vod['id'], 'video' => 'https://www.youtube.com/embed/'.$video]);
				}
				
				break;
			}
		}
	}
}
public function save(){
	Yii::$app->db->createCommand($this->sql)->execute();
}

}



?>