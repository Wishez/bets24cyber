<?php

namespace app\models;

use GlobIterator;
use Yii;

/**
 * This is the model class for table "team".
 *
 * @property integer $id_team
 * @property integer $id_steam_team
 * @property string $team_name
 * @property string $country_code
 * @property integer $team_logo
 */
class Player extends \yii\db\ActiveRecord
{

public $game1;
public $name1;
public $text;
public $sort;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'players';
    }
    public function rules()
    {
        return [
            [['team_id', 'name', 'country', 'earning', 'age', 'role', 'game', 'player_id', 'image'], 'safe'],
        ];
    }
    public function getFl()
    {
        return $this->hasOne(Flag::className(), ['country' => 'country']);
    }
    public function getOverview(){
        return self::find()->select('text, name, sort')->from('teams_players_about')->where(['id' => $this->player_id, 'game' => $this->game])->orderBy('sort')->all();
    }
    public function getFlagUrl(){
        $flag = 'eflag';
        if(!empty($this->fl->flag)){
            $flag = $this->fl->flag;
        }
        return '/img/flags/'.$flag.'.png';
    }
        public function getTeam(){
        return $this->hasOne(Team::className(), ['team_id' => 'team_id']);
    }
    /**
     * @inheritdoc
     */


}
