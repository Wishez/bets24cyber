<?php

namespace app\modules\forum\controllers;

use app\modules\forum\models\ForumCategory;
use app\modules\forum\models\ForumTopic;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionIndex($id)
    {
        $childCat = ForumCategory::getChildCategory($id);
        $topic = ForumTopic::find()->where(['id_fcategory' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $topic,
        ]);
//        var_dump($topic);
        return $this->render('category', ['childCat' => $childCat, 'dataProvider' => $dataProvider, 'id_category' => $id]);
    }
}
