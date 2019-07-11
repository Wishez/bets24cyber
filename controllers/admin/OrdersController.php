<?php

namespace app\controllers\admin;

use Yii;
use app\models\Orders;
use app\models\Match;
use app\models\OrdersSearch;
use app\models\MatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = "admin";
    
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!\Yii::$app->user->can('просмотр раздела - ордеры')) {
            throw new ForbiddenHttpException('Access denied');
        }
        
        $searchModel = new MatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('matches', [
            
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrdersList($id)
    {
        if (!\Yii::$app->user->can('просмотр раздела - ордеры')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Match::findOne($id);
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'model'=>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!\Yii::$app->user->can('просмотр деталей ордера')) {
            throw new ForbiddenHttpException('Access denied');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new Orders();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('create', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionScore($id)
    {
        if (!\Yii::$app->user->can('возможность изменить победителя матча')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Match::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['orders-list', 'id' => $model->id]);
        } else {
             var_dump($model->errors);
             exit();
        }
    }


    public function actionAddTime($id, $time)
    {
        $model = Match::findOne($id);
        $model->updateCounters(['orders_time' => $time]);
        return $this->redirect(['orders-list', 'id' => $model->id]);
    }

    public function actionSuccess($id)
    {
        if (!\Yii::$app->user->can('смена статуса ордера')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Match::findOne($id);
        foreach ($model->orders as $key => $order) {
            foreach ($order->getPays()->andWhere(['status'=>0])->all() as $key => $pay) {
                $pay->goSuccess();
                $order->addLog("Транзакция #".$pay->id.", на пользователя #".$pay->partner.", на сумму ".$pay->amount.", в статусе ".$pay->statusText);
                $order->addLog("Ордер #".$pay->id." - Подтвержден!");
                //$order->addLog("Ставка пользователя ".$pay->partner." выиграна на сумму ".$pay->amount.".");
            }
        }
        $model->updateCounters(['orders_time' => $time]);
        return $this->redirect(['orders-list', 'id' => $model->id]);
    }
        

    public function actionChange($id)
    {
        if (!\Yii::$app->user->can('возможность заново обработать ордер')) {
            throw new ForbiddenHttpException('Access denied');
        }

        $model = Match::findOne($id);
        foreach ($model->orders as $key => $order) {
            foreach ($order->pays as $key => $pay) {
                $pay->goDisable();
            }
            $order->processing();
        }
        $model->updateCounters(['orders_time' => $time]);
        return $this->redirect(['orders-list', 'id' => $model->id]);
    }

    public function actionGetting($id)
    {
        $model = Match::findOne($id);
        if ($model->orders_active) {
            $model->orders_active = 0;
        }
        else $model->orders_active = 1;
        $model->save(false);
        return $this->redirect(['orders-list', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('возможность удаления ордеров')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
