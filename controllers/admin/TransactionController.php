<?php

namespace app\controllers\admin;

use Yii;
use app\models\User;
use app\models\Transaction;
use app\models\Funds;
use app\models\TransactionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class TransactionController extends Controller
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
    public function actionIndex($id=false)
    {
        if (!\Yii::$app->user->can('просмотр раздела транзакции')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        //$model = Funds::find();
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    public function actionCreate()
    {
        if (Yii::$app->request->get('type')==0&&!\Yii::$app->user->can('совершения транзакции пополнение')) {
            throw new ForbiddenHttpException('Access denied');
        }
        if (Yii::$app->request->get('type')==1&&!\Yii::$app->user->can('совершения транзакции возврат клиенту')) {
            throw new ForbiddenHttpException('Access denied');
        }
        if (Yii::$app->request->get('type')==4&&!\Yii::$app->user->can('совершения транзакции оплата ордера')) {
            throw new ForbiddenHttpException('Access denied');
        }
        if (Yii::$app->request->get('type')==2&&!\Yii::$app->user->can('совершения транзакции перевод из фонда в фонд')) {
            throw new ForbiddenHttpException('Access denied');
        }
        if (Yii::$app->request->get('type')==0&&!\Yii::$app->user->can('совершения транзакции пополнение')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->run()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', ['model' => $model, 'type' => Yii::$app->request->get('type')]);
        }
    }
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('редактирования транзакции')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Transaction::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            return $this->redirect('index');
        } else {
            $model->newAgents();
            return $this->render('update', ['model' => $model]);
        }
    }
    // public function actionDelete($id){
    //     if (!\Yii::$app->user->can('редактирования транзакции')) {
    //         throw new ForbiddenHttpException('Access denied');
    //     }
    //  $model = UserFields::findOne($id);
    //  if($model !== null){
    //      $model->removeColumn();
    //      $model->delete();
    //  }
    //  return $this->redirect('index');
    // }
    public function actionGo($id)
    {
        if (!\Yii::$app->user->can('подтверждения транзакции')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Transaction::findOne($id);
        if ($model !== null) {
            $model->goSuccess();
        }
        return $this->redirect('index');
    }

    public function actionDisable($id)
    {
        if (!\Yii::$app->user->can('отмена транзакции')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = Transaction::findOne($id);
        if ($model !== null) {
            $model->goDisable();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionFundName($term)
    {
        if (preg_match('/\(#(\d+?)\)/', $term, $m)) {
            $term = $m[1];
        }
        $funds = Funds::find()->where('name LIKE "%'.$term.'%"')->orWhere(['id' => $term])->asArray()->all();
        $funds_names = [];

        foreach ($funds as $fund) {
            array_push($funds_names, 'Фонд "'.$fund['name'].'" (#'.$fund['id'].')');
        }
        return json_encode($funds_names);
    }
    public function actionUserName($term)
    {
        if (preg_match('/\(#(\d+?)\)/', $term, $m)) {
            $term = $m[1];
        }
        $users = User::find()->where('username LIKE "%'.$term.'%"')->orWhere('email LIKE "%'.$term.'%"')->orWhere(['id' => $term])->asArray()->all();
        $users_names = [];

        foreach ($users as $user) {
            array_push($users_names, $user['username'].' ('.$user['email'].') Баланс: '.$user['balance'].' (#'.$user['id'].')');
        }
        return json_encode($users_names);
    }
}
