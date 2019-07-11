<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "steam_user".
 *
 * @property integer $id_staem_user
 * @property integer $id_steam_team
 * @property string $name
 * @property integer $role
 */
class SteamUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'steam_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_staem_user', 'id_steam_team'], 'required'],
            [['id_staem_user', 'id_steam_team', 'role'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_staem_user' => 'Id Staem User',
            'id_steam_team' => 'Id Steam Team',
            'name' => 'Name',
            'role' => 'Role',
        ];
    }
}
