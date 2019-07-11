<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favorites".
 *
 * @property integer $id_user
 * @property integer $id_match
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_match'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_match' => 'Id Match',
        ];
    }
    /**
     * Get combined primary key
     * @return array
     */
    public static function primaryKey() {
        return ['id_user', 'id_match'];
    }
}
