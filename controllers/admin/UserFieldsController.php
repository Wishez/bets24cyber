<?php

namespace app\controllers\admin;

use Yii;
use app\models\UserFields;
use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserFieldsController extends Controller
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
        if (!\Yii::$app->user->can('просмотр полей пользователя')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = UserFields::find();
        return $this->render('index', ['model' => $model]);
    }
    public function actionCreate()
    {

        if (!\Yii::$app->user->can('добавление полей пользователя')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = new UserFields();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$model->addColumn();
            return $this->redirect('index');
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('редактирование полей пользователя')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = UserFields::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }
    public function actionDelete($id){
        if (!\Yii::$app->user->can('удаление полей пользователя')) {
            throw new ForbiddenHttpException('Access denied');
        }
    	$model = UserFields::findOne($id);
    	if($model !== null){
    		$model->removeColumn();
    		$model->delete();
    	}
    	return $this->redirect('index');
    }

}
