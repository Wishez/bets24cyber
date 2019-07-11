<?php

namespace app\controllers\admin;

use Yii;
use app\models\Funds;
use app\models\FundSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
/**
 * UserController implements the CRUD actions for User model.
 */
class FundsController extends Controller
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
        if (!\Yii::$app->user->can('просмотр раздела - фонды')) {
            throw new ForbiddenHttpException('Access denied');
        }

        $searchModel = new FundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$model = Funds::find();
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    public function actionCreate()
    {

        if (!\Yii::$app->user->can('создание фонда')) {
            throw new ForbiddenHttpException('Access denied');
        }

        $model = new Funds();
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }
    public function actionUpdate($id)
    {

        if (!\Yii::$app->user->can('редактирование фонда')) {
            throw new ForbiddenHttpException('Access denied');
        }

        $model = Funds::findOne($id);
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }
    public function actionDelete($id){

        if (!\Yii::$app->user->can('удаление фонда')) {
            throw new ForbiddenHttpException('Access denied');
        }
    	$model = Funds::findOne($id);
    	if($model !== null){
    		$model->delete();
    	}
    	return $this->redirect('index');
    }

}
