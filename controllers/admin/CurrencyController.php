<?php

namespace app\controllers\admin;

use Yii;
use app\models\Currency;
use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
/**
 * UserController implements the CRUD actions for User model.
 */
class CurrencyController extends Controller
{

    public $layout = "admin";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'rules' => [
                    [
                        'allow' => true,
                        //'roles' => ['admin'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!\Yii::$app->user->can('просмотр раздела - валюты')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Currency::find();
        return $this->render('index', ['model' => $model]);
    }
    public function actionCreate()
    {
        if (!\Yii::$app->user->can('создание валюты')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = new Currency();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('редактирование валюты')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Currency::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }
    public function actionDelete($id){
        if (!\Yii::$app->user->can('удаление валюты')) {
            throw new ForbiddenHttpException('Access denied');
        }
    	$model = Currency::findOne($id);
    	if($model !== null){
    		$model->delete();
    	}
    	return $this->redirect('index');
    }

}
