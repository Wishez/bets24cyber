<?php

namespace app\models;

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
class Fork extends \yii\db\ActiveRecord
{

	public $link1;
	public $link2;
    public $link;
    public $img;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forks';
    }

    public function rules()
    {
        return [
            [['k1', 'k2', 'bk1', 'bk2', 'profit', 'match_id', 'date'], 'safe']
        ];
    }
    public static function getEmail(){
    	return (new Query)->select('*')->from('fork_email')->all();
    }
    public function getBk1_link(){
        return (new Query)->select('link, img')->from('bk_desc')->where(['type' => $this->bk1])->one();
    }
    public function getBk2_link(){
        return (new Query)->select('link, img')->from('bk_desc')->where(['type' => $this->bk2])->one();
    }
    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }

}
