<?php

namespace app\modules\forum\controllers;

use app\modules\forum\models\ForumCategory;
use app\modules\forum\models\ForumTopic;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $categorys = ForumCategory::getCategorys();
        return $this->render('index',['categorys'=>$categorys]);
    }
}
