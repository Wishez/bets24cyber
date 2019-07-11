<?php

namespace app\modules\forum\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "forum_category".
 *
 * @property integer $id_fcategory
 * @property string $name
 * @property integer $id_owner_category
 *
 * @property ForumCategory $idOwnerCategory
 * @property ForumCategory[] $forumCategories
 * @property ForumTopic[] $forumTopics
 */
class ForumCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum2_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_owner_category'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_fcategory' => 'Id Fcategory',
            'name' => 'Name',
            'id_owner_category' => 'Id Owner Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOwnerCategory()
    {
        return $this->hasOne(ForumCategory::className(), ['id_fcategory' => 'id_owner_category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForumCategories()
    {
        return $this->hasMany(ForumCategory::className(), ['id_owner_category' => 'id_fcategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForumTopics()
    {
        return $this->hasMany(ForumTopic::className(), ['id_fcategory' => 'id_fcategory']);
    }

    public static function getCategorys()
    {
        $res =self::find()->where('id_fcategory <> 0')->asArray()->all();
//        return ArrayHelper::map($res,'id_fcategory','name','id_owner_category');
        return $res;
    }
    public static function getChildCategory($id){
        return self::find()->where(['id_owner_category'=>$id])->asArray()->all();
    }
}
