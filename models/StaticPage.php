<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "static_page".
 *
 * @property integer $id_statp
 * @property string $title
 * @property string $short_description
 * @property string $description
 */
class StaticPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $href;


    public static function tableName()
    {
        return 'static_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_description', 'description'], 'required'],
            [['description'], 'string'],
            [['title', 'short_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_statp' => 'Id Statp',
            'title' => 'Название',
            'short_description' => 'Краткое описани',
            'description' => 'Текст',
            'href'=>'Ссылка на ресурс',
        ];
    }
}
