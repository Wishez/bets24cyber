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
class DotaMatch extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'dota_games';
    }



}


?>