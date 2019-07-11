<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bookmaker_rate".
 *
 * @property integer $id_brate
 * @property integer $id_bmaker
 * @property integer $id_match
 * @property string $koef_team_1
 * @property string $koef_team_2
 */
class BookmakerRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $teamList;
    public $bookMakerList;
    public $bookmaker_name;


    public static function tableName()
    {
        return 'bookmaker_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_bmaker', 'id_match', 'koef_team_1', 'koef_team_2'], 'required'],
            [['id_bmaker', 'id_match'], 'integer'],
            [['koef_team_1', 'koef_team_2'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_brate' => 'Id Brate',
            'id_bmaker' => 'Название букмекера',
            'id_match' => 'Id Match',
            'koef_team_1' => 'Koef Team 1',
            'koef_team_2' => 'Koef Team 2',
            'teamList' => 'Матч',
            'bookMakerList' => 'Букмекеры',
            'bookmaker_name' => 'Название букмекера',
        ];
    }

    public static function koefMatch($idMatch)
    {
        return self::find()
            ->select('bookmaker_rate.id_match,
                bookmaker_rate.id_brate,
                bookmaker_rate.id_bmaker,
                bookmaker_rate.koef_team_1,
                bookmaker_rate.koef_team_2,
                bookmaker.logo,
                match.team1,
                league.logo as league_logo,
                match.team2,
                match.team_parser_data'
            )
            ->leftJoin('match', 'match.id_match=bookmaker_rate.id_match')
            ->leftJoin('bookmaker', 'bookmaker.id_bmaker=bookmaker_rate.id_bmaker')
            ->leftJoin('league', 'league.id_league=match.id_league')
            ->where(['bookmaker_rate.id_match' => $idMatch]);
    }
}
