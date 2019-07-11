<?php

namespace app\models;

use GlobIterator;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "team".
 *
 * @property integer $id_team
 * @property integer $id_steam_team
 * @property string $team_name
 * @property string $country_code
 * @property integer $team_logo
 */
class Team extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */

    public $place;
    public $stat;
    public $prize;
    public $text;
    public $sort;

    // public $text0;
    // public $text1;
    // public $text2;
    //  public $text3;
    //   public $text4;
    //    public $text5;



    public static function tableName()
    {
        return 'teams';
    }
    public function getCountry()
    {
        return $this->hasOne(Flag::className(), ['country' => 'flag']);
    }
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['team_id' => 'team_id'])->with('fl')->limit(5);
    }
    public function allMatches()
    {
        return Match::find()
        	->select('m.*, ISNULL(f.id_user) as favorite')
        	->alias('m')
        	->LeftJoin('favorites f', 'f.id_match = m.id AND f.id_user = '.(empty(Yii::$app->user->id) ? 0 : Yii::$app->user->id)) 
        	->where(['or', ['team1' => $this->team_id], ['team2' => $this->team_id]])
            ->andWhere(['active' => 1]);
    }
    public function getLastMatches(){
        return Match::allMatches()->andWhere(['or', ['m.team1' => $this->team_id], ['m.team2' => $this->team_id]])->andWhere(['m.over' => 1, 'l.game' => $this->game])
        ->with('league')->with('bets')->orderBy('m.date DESC');
    }
    public function getActiveMatches(){
        return Match::allMatches()->andWhere(['or', ['m.team1' => $this->team_id], ['m.team2' => $this->team_id]])->andWhere(['m.over' => 0, 'l.game' => $this->game])
        ->with('league')->with('dotaGames')->with('csgoGames')->with('bets')->orderBy('m.date DESC');
    }
    public function getHeadToHead($team2){
        return Match::allMatches()->andWhere(['or', ['and', ['m.team1' => $this->team_id], ['m.team2' => $team2]], ['and', ['m.team2' => $this->team_id], ['m.team1' => $team2]]])
        ->with('league')->with('dotaGames')->with('csgoGames')->with('bets')->andWhere(['m.over' => 1, 'l.game' => $this->game])->orderBy('m.date DESC');
    }
    public function getOverview(){
        $a = self::find()->select('text, name, sort')->from('teams_players_about')->where(['id' => $this->team_id, 'game' => $this->game])->orderBy('sort')->all();
        // foreach ($a as $key => $value) {
        //     $a[$key]['text'] = htmlspecialchars_decode($a[$key]['text']);
        // }
        return $a;
    }
    public function getStats(){
        $matches = Match::find()->where(['or', ['team1' => $this->team_id], ['team2' => $this->team_id]])->asArray()->all();
        $wins = 0;

        foreach ($matches as $match) {
            if($match['team1'] == $this->team_id){
                if($match['score_left'] > $match['score_right']){
                    $wins++;
                }
            }else{
                if($match['score_left'] < $match['score_right']){
                    $wins++;
                }   
            }

        }
        if(count($matches) > 0){
            $this->stat = ['games' => count($matches), 'wins' => round($wins / count($matches) * 100, 0)];
        }else{
            $this->stat = ['games' => 0, 'wins' => 0];
        }
        
        return $this->stat;
    }
    public function getNames(){
        return $this->hasMany(TeamName::className(), ['team_id' => 'id']);
    }
    public function getPlaces(){
        $players = self::find()->select('l.id, l.name, p.place, prize')->from('league_place p')->leftJoin('leagues l', 'l.id LIKE p.league_id')->where(['p.team' => $this->team_id])->all();
        usort($players, function($a, $b){
            $a = explode('-', $a->place)[0];
            $b = explode('-', $b->place)[0];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        return $players;
    }
    public function rules()
    {
        return [
            [['team_id', 'name', 'flag', 'earning', 'flag', 'logo', 'created', 'game', 'captain', 'spec_id', 'updated'], 'safe'],
            ['text', 'each', 'rule' => ['integer']]
        ];
    }
    public function getFlagUrl(){
    	// $flag = 'eflag';
    	// if(!empty($this->country->flag)){
    	// 	$flag = $this->country->flag;
    	// }
    	// return '/img/flags/'.$flag.'.png';
        return $this->flag;
    }
    public function getFl(){
        $flag = 'eflag';
        if(!empty($this->country->flag)){
         $flag = $this->country->flag;
        }
        return '/img/flags/'.$flag.'.png';     
    }

}
