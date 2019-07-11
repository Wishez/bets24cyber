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
class TeamName extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */

    public $place;
    public $stat;
    public $prize;
    public $text;


    public static function tableName()
    {
        return 'team_names';
    }

}
