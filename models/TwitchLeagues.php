<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "twitch_leagues".
 *
 * @property integer $id
 * @property integer $leagues_id
 * @property string $channel
 * @property string $country
 * @property integer $sort
 */
class TwitchLeagues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'twitch_leagues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'leagues_id', 'channel', 'country'], 'required'],
            [['id', 'leagues_id', 'sort'], 'integer'],
            [['channel'], 'string', 'max' => 300],
            [['country'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leagues_id' => 'Leagues ID',
            'channel' => 'Channel',
            'country' => 'Country',
            'sort' => 'Sort',
        ];
    }

    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'leagues_id']);
    }
}
