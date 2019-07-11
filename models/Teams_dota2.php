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
class Teams_dota2 extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teams_dota2';
    }


}
