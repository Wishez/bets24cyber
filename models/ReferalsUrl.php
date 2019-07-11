<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referals_url".
 *
 * @property integer $id_ref
 * @property integer $user_id
 * @property string $ref_url
 */
class ReferalsUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referals_url';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ref_url'], 'required'],
            [['user_id'], 'integer'],
            [['ref_url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_ref' => 'Id Ref',
            'user_id' => 'User ID',
            'ref_url' => 'Ref Url'
        ];
    }
}
