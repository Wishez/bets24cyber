<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\League;
use app\models\Match;
use yii\db\Query;
use app\models\GetTeams;
use app\models\TeamData;
use app\models\LoadData;
use app\models\ParseBracket;
use \Exception;


class SaveLeague{
	private $league;
	private $matches;
	private $teams;

	function __construct($data){
		if(!empty($data['league'])){
			$this->league = $data['league'];
			$this->matches = !empty($data['matches']) ? $data['matches'] : [];
			$this->teams = !empty($data['teams']) ? $data['teams'] : [];
		}else throw new Exception('Пустые данные');
	}
	public function save(){
		$transaction = Yii::$app->db->beginTransaction();
		$league = League::findOne(['league_id' => $this->league['league_id']]);

		if($league){
			$league->attributes = $this->league;
		}else{
			$league = new League();
			$league->attributes = $this->league;
		}
		$sql = '';
		if($league->save()){
			$matches = Match::find()->where(['league_id' => $league->id])->orderBy('date')->asArray()->all();
			foreach ($this->matches as $match) {
				$find = false;
				$match['league_id'] = $league->id;
				foreach ($matches as $key => $bmatch) {
					if($match['team1'] == $bmatch['team1'] && $match['team2'] == $bmatch['team2']){
						if(strtotime($bmatch['date']) <= strtotime($match['date']) + 60 * 60 && strtotime($bmatch['date']) >= strtotime($match['date']) - 60 * 60){
								$match['active'] = 1;
								$sql .= Yii::$app->db->createCommand()->update('matches', $match, 'id = '.$bmatch['id'])->getRawSql().'; ';
								unset($matches[$key]);
								$find = true;
								break;
						}
					}
				}
				if(!$find){
					$sql .= Yii::$app->db->createCommand()->insert('matches', $match)->getRawSql().'; ';
				}
			}
			foreach ($matches as $match) {
				$sql .= Yii::$app->db->createCommand()->update('matches', ['active' => 0], 'id = '.$match['id'])->getRawSql().'; ';
			}
			$sql .= Yii::$app->db->createCommand()->delete('league_place', 'league_id = '.$league->id)->getRawSql().'; ';
			foreach ($this->teams as $team) {
				$team['league_id'] = $league->id;
				$sql .= Yii::$app->db->createCommand()->insert('league_place', $team)->getRawSql().'; ';
			}

			if($this->league['event'] == 1 && !empty($this->league['main'])){
					$qual = [];
					$qual['league_id'] = $this->league['main'];
					$qual['qual_id'] = $league->id;
					$qual['name'] = $this->league['qname'];

					$sql .= Yii::$app->db->createCommand()->insert('league_quals', $qual)->getRawSql().'; ';
			}
			$brackets = new ParseBracket($this->league['league_id'], $this->league['game']);
			$sql .= Yii::$app->db->createCommand()->delete('league_bracket', 'league_id = '.$league->id)->getRawSql().'; ';
			foreach ($brackets->get() as $bracket) {
				$bracket['league_id'] = $league->id;
				$sql .= Yii::$app->db->createCommand()->insert('league_bracket', $bracket)->getRawSql().'; ';

			}
			$sql .= $brackets->saveTable($league->id);
			$sql .= $brackets->saveGroups($league->id);

			//echo $sql;
			try{
				Yii::$app->db->createCommand($sql)->execute();
				$transaction->commit();
			}catch(Exception $e){
				$transaction->rollback();
				//print_r($this->league);
				throw new Exception("Ошибка при сохранении");
			}
		}else{
			$transaction->rollback();
			throw new Exception("Ошибка при сохранении");
		}
		


		// $sql = 'DELETE FROM league_games WHERE league_id = "'.$this->league->league_id.'"; 
		// DELETE FROM league_links WHERE league_id = "'.$this->league->league_id.'"; 
		// DELETE FROM league_bracket WHERE league_id = "'.$this->league->league_id.'";
		// DELETE FROM league_streams WHERE league_id = "'.$this->league->league_id.'";  ';

		// foreach ($this->matches as $value) {
		// 	$value->league_id = $this->league->league_id;
		// 	$value->over = isset($value->over) && $value->over == "true" ? 1 : 0;
		// 	//print_r($value);
		// 	if(!empty($value->date) && isset($value->date)){
		// 		$sql .= LoadData::insert('league_games', $value);
		// 	}
			
		// }
		// foreach ($this->quals as $value) {
		// 	$value->league_id = $this->league->league_id;
		// 	$league = LoadData::parseLink($value->link);
		// 	$value->link = $league['league_id'];
		// 	$sql .= LoadData::insert('league_links', $value);

		// }
		// foreach ($this->teams as $value) {
		// 	$value->league_id = $this->league->league_id;
		// 	unset($value->players);
		// 	$sql .= LoadData::insert('league_place', $value);

		// }
		// foreach ($this->streams as $value) {
		// 	$value->league_id = $this->league->league_id;
		// 	$sql .= LoadData::insert('league_streams', $value);

		// }
		// $bracket = new ParseBracket($this->league->league_id, $this->league->game);

		// $bracket_data = $bracket->get();

		// // print_r($bracket_data);
		// foreach ($bracket_data['details'] as $value) {
		// 	$sql .= LoadData::insertDuplicate('league_bracket_games', ['ix' => $value['index'], 'data' => json_encode($value['game'])]);
		// }
		// foreach ($bracket_data['bracket'] as $value) {
		// 	$sql .= 'INSERT INTO league_bracket VALUES("'.$this->league->league_id.'", "'.$value['name'].'", "'.$value['bracket'].'"); ';
		// }
		// //print_r($bracket_data);

		// $sql .= LoadData::insertDuplicate('leagues', $this->league);
		// Yii::$app->db->createCommand($sql)->execute();
	}
}
?>