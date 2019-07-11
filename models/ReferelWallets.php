<?php

namespace app\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "referel_wallets".
 *
 * @property integer $id_user
 * @property string $wallet
 */
class ReferelWallets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referel_wallets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user'], 'integer'],
            [['wallet'], 'string'],
            [['wallet'], 'filter', 'filter' => function ($value) {
                return strip_tags($value);
            }]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'wallet' => 'Кошелек',
        ];
    }
}
