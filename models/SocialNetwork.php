<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "social_network".
 *
 * @property integer $id_snetwork
 * @property string $logo
 * @property string $href
 * @property integer $enable
 */
class SocialNetwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_network';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logo', 'href', 'name'], 'required'],
            [['enable'], 'integer'],
            [['logo'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['href'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_snetwork' => 'Id Snetwork',
            'logo' => 'Логотип',
            'href' => 'Ссылка',
            'enable' => 'Enable',
            'name'=>'Название социальной сети',
        ];
    }


    public function getSocElem()
    {
        $model = SocialNetwork::findBySql('SELECT logo,href,enable FROM social_network WHERE enable="1"')->all();
        return $model;
    }
}
