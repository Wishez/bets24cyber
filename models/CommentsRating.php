<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments_rating".
 *
 * @property integer $id_rait
 * @property integer $id_comment
 * @property integer $id_user
 * @property double $rait
 */
class CommentsRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments_rating';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_comment', 'id_user'], 'integer'],
            [['rait'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_rait' => 'Id Rait',
            'id_comment' => 'Id Comment',
            'id_user' => 'Id User',
            'rait' => 'Rait',
        ];
    }

    public static function getRait($id_comment)
    {
        $raits = self::find()->select('rait')->where(['id_comment' => $id_comment])->asArray()->all();
        $res = 0;
        foreach ($raits as $rait) {
            $res += (int)$rait['rait'];
        }
        return $res;
    }
    public static function getRaits($id_comments)
    {
        $raits = self::find()->select('id_comment,rait')->where(['id_comment' => $id_comments])->asArray()->all();
        $res = 0;
        foreach ($raits as $rait) {
            $res += (int)$rait['rait'];
        }
        return $res;
    }
    

    public static function setRaiting($comment_id, $sign)
    {
        $res = Comments::findOne(['id_comment'=>$comment_id,'user_id'=>Yii::$app->user->id]);
        if(!$res){
            $sign = $sign == 'plus' ? 1 : -1;
            $res = self::findOne(['id_user' => Yii::$app->user->id, 'id_comment' => $comment_id]);
//        var_dump($res);
            if ($res) {
                $res->rait = ($res->rait > 0 && $sign < 0) ? 0 : (($res->rait > 0 && $sign > 0) ? 1 : (($res->rait < 0 && $sign < 0) ? -1 : $res->rait + $sign));
                $res->save();
            } else {
                $res = new self;
                $res->id_comment = $comment_id;
                $res->id_user = Yii::$app->user->id;
                $res->rait = $sign;
                $res->save();
            }
        }

        $res = self::find()->where(['id_comment' => $comment_id])->sum('rait');//asArray()->all();
        if ($res) {
           /* $rait = 0;
            foreach ($res as $r) {
                $rait += (int)$r['rait'];
            }*/
            return $res;
        }
        return 0;
    }
}
