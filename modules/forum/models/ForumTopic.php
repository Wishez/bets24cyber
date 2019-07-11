<?php

namespace app\modules\forum\models;

use app\models\User;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "forum_topic".
 *
 * @property integer $id_ftopic
 * @property string $name
 * @property integer $id_fcategory
 * @property integer $id_user_owner
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $views
 * @property integer $enable
 * @property integer $visible
 *
 * @property ForumPost[] $forumPosts
 * @property ForumCategory $idFcategory
 * @property User $idUserOwner
 */
class ForumTopic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum2_topic';
    }
    public function behaviors()
    {

        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_INSERT, function ($event) {
            $this->id_user_owner = Yii::$app->user->id;
            $this->visible = 1;
            $this->enable = 1;
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
            [['id_fcategory', 'id_user_owner', 'created_at', 'updated_at', 'views', 'enable', 'visible'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name','id_fcategory'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_ftopic' => 'Id Ftopic',
            'name' => 'Название темы',
            'id_fcategory' => 'Id Fcategory',
            'id_user_owner' => 'Id User Owner',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'views' => 'Views',
            'enable' => 'Enable',
            'visible' => 'Visible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForumPosts()
    {
        return $this->hasMany(ForumPost::className(), ['id_ftopic' => 'id_ftopic']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFcategory()
    {
        return $this->hasOne(ForumCategory::className(), ['id_fcategory' => 'id_fcategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUserOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user_owner']);
    }
}
