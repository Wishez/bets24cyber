<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "button_cap".
 *
 * @property integer $id_bc
 * @property string $name
 * @property string $link
 */
class ButtonCap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'button_cap';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'link'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_bc' => 'Id Bc',
            'name' => 'Name',
            'link' => 'Link',
        ];
    }
}
