<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id_banner
 * @property string $img
 * @property string $link
 * @property string $alt_text
 * @property integer $active
 */
class Banners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['img'], 'required'],
            [['img', 'link', 'alt_text'], 'string'],
            [['active'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_banner' => 'Id Banner',
            'img' => 'Img',
            'link' => 'Link',
            'alt_text' => 'Alt Text',
            'active' => 'Active',
        ];
    }
    
    public static function getBanners(){
        return self::find()->where(['active'=>'1']);
    }
}
