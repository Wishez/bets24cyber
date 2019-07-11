<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "top_streams".
 *
 * @property integer $id_tsream
 * @property string $title
 * @property string $date
 * @property integer $views
 * @property integer $likes
 * @property string $link
 * @property string $img
 * @property integer $visible
 */
class TopStreams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'top_streams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','date','views','likes'], 'required'],
            [['date'], 'safe'],
            [['views', 'likes', 'visible'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['link', 'img'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tsream' => 'Id Tsream',
            'title' => 'Название',
            'date' => 'Дата',
            'views' => 'Количество просмотров',
            'likes' => 'Likes',
            'link' => 'Ссылка на стрим',
            'img' => 'Изображение',
            'visible' => 'Visible',
        ];
    }
}
