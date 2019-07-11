<?php

namespace app\models;

use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id_comment
 * @property integer $user_id
 * @property integer $match_id
 * @property string $text
 * @property integer $active
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $news
 */
class Comments extends \yii\db\ActiveRecord
{

    public $username;
    public $avatar;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    public function behaviors()
    {
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_INSERT, function ($event) {
            $this->user_id = Yii::$app->user->id;
            $this->text = Yii::$app->formatter->asNtext($this->text);
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
            [['match_id', 'text'], 'required'],
            [['user_id', 'match_id', 'active', 'parent_id', 'created_at', 'updated_at', 'news'], 'integer'],
            [['text'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_comment' => 'Id Comment',
            'user_id' => 'User ID',
            'match_id' => 'Match ID',
            'text' => 'Text',
            'active' => 'Active',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'news' => 'News'
        ];
    }

    public static function getComments($id_match, $news = false)
    {
        if (!$news) {
            $comments = self::find()
                ->select(['user.username',
                        'user.id as user_id',
                        'user.avatar',
                        'comments.id_comment',
                        'comments.text',
                        'comments.created_at',
                        '(SELECT SUM(comments_rating.rait) FROM comments_rating WHERE comments_rating.id_comment = comments.id_comment)as rait']
                )
                ->leftJoin('user', 'user.id = comments.user_id')
                ->where(['active' => '1', 'match_id' => $id_match, 'parent_id' => 0, 'news' => 0])->orderBy(['created_at' => SORT_DESC])->asArray()->all();
        } else {
            $comments = self::find()
                ->select(['user.username',
                        'user.id as user_id',
                        'user.avatar',
                        'comments.id_comment',
                        'comments.text',
                        'comments.created_at',
                        '(SELECT SUM(comments_rating.rait) FROM comments_rating WHERE comments_rating.id_comment = comments.id_comment)as rait']
                )
                ->leftJoin('user', 'user.id = comments.user_id')
                ->where(['active' => '1', 'match_id' => $id_match, 'parent_id' => 0, 'news' => 1])->orderBy(['created_at' => SORT_DESC])->asArray()->all();
        }

        return $comments;
    }

    public static function hideComment($id_comment)
    {
        $comment = self::findOne(['id_comment' => $id_comment]);
        if ($comment) {
            $comment->active = 0;
            if ($comment->save()) {
                return true;
            }
        }

        return false;
    }
}
