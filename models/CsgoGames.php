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
class CsgoGames extends \yii\db\ActiveRecord
{

	public $series;
    public static function tableName()
    {
        return 'hltv_matches';
    }



}


?>