<?php

namespace app\modules\forum\models;

use app\models\User;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "forum_post".
 *
 * @property integer $id_fpost
 * @property integer $id_ftopic
 * @property integer $id_user
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $description
 *
 * @property ForumLike[] $forumLikes
 * @property ForumTopic $idFtopic
 * @property @app/models/User $idUser
 */
class ForumPost extends \yii\db\ActiveRecord
{
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum2_post';
    }

    public function behaviors()
    {

        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_INSERT, function ($event) {
            $this->id_user = Yii::$app->user->id;
        });
        return [
            TimestampBehavior::className(),

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ftopic', 'id_user', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['description'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_fpost' => 'Id Fpost',
            'id_ftopic' => 'Id Ftopic',
            'id_user' => 'Id User',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'description' => 'Сообщение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForumLikes()
    {
        return $this->hasMany(ForumLike::className(), ['id_fpost' => 'id_fpost']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFtopic()
    {
        return $this->hasOne(ForumTopic::className(), ['id_ftopic' => 'id_ftopic']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
