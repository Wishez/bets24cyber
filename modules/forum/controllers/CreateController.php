<?php

namespace app\modules\forum\controllers;

use app\modules\forum\models\ForumPost;
use app\modules\forum\models\ForumTopic;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 23.02.2016
 * Time: 15:40
 */
class CreateController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionTopic($id_cat)
    {
        $modelTopic = new ForumTopic();
        $modelPost = new ForumPost();

        if (\Yii::$app->request->isPost) {
            if($modelTopic->save()){
                if($modelPost->save()){
                    return $this->redirect('/');
                }
            }
        }

        return $this->render('addTopic', ['modelTopic' => $modelTopic, 'modelPost' => $modelPost, 'id_cat' => $id_cat]);
    }

}