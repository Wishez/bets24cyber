<?php

namespace app\modules\forum\models;

use Yii;

/**
 * This is the model class for table "forum_like".
 *
 * @property integer $id_flike
 * @property integer $id_fpost
 * @property integer $count
 * @property string $forum_likes_user_list
 *
 * @property ForumPost $idFpost
 */
class ForumLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum2_like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_fpost', 'count'], 'integer'],
            [['forum_likes_user_list'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_flike' => 'Id Flike',
            'id_fpost' => 'Id Fpost',
            'count' => 'Count',
            'forum_likes_user_list' => 'Forum Likes User List',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFpost()
    {
        return $this->hasOne(ForumPost::className(), ['id_fpost' => 'id_fpost']);
    }
}
