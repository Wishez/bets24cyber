<?php

namespace app\modules\forum\controllers;

use app\modules\forum\models\ForumCategory;
use app\modules\forum\models\ForumPost;
use app\modules\forum\models\ForumTopic;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TopicController extends Controller
{
    public function actionIndex($id)
    {
        $posts = ForumPost::find()->select(['id_fpost','description','username'])->leftJoin('user','id_user= id')->where(['id_ftopic'=>$id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $posts,
        ]);
//        var_dump($posts->all());
        return $this->render('topic',['dataProvider'=>$dataProvider]);
    }
}
