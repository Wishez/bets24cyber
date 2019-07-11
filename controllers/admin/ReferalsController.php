<?php

namespace app\controllers\admin;

use app\models\ReferalsDetail;
use app\models\ReferalsUrl;
use app\models\ReferelWallets;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ReferalsController extends Controller
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
                        'roles' => ['admin'],
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

    public function actionIndex()
    {
        $referals = User::find()->where(['role' => 'partner']);
        $referalstDataProvider = new ActiveDataProvider([
            'query' => $referals,
            'pagination' => ['pageSize' => 10,]
        ]);
        return $this->render('index', [
            'referals' => $referalstDataProvider
        ]);
    }

    public function actionView($id)
    {
        $referal_url = ReferalsUrl::find()->where(['user_id' => $id]);
        $wallet = ReferelWallets::findOne(['id_user' => $id]);

        return $this->render('view', [
            'referal_url' => $referal_url,
            'user_id' => $id,
            'wallet' => $wallet ? $wallet->wallet : ""
        ]);
    }

    public function actionCreate($id)
    {
        $referal_url = new ReferalsUrl();

        if ($referal_url->load(Yii::$app->request->post()) && $referal_url->save()) {
            return $this->redirect(['view', 'id' => $referal_url->user_id]);
        } else {
            return $this->render('create', [
                'referal_url' => $referal_url,
                'user_id' => $id
            ]);
        }
    }

    public function actionEditRefUrl($id)
    {
        $referal_url = ReferalsUrl::findOne($id);

        if ($referal_url->load(Yii::$app->request->post()) && $referal_url->save()) {
            return $this->redirect(['view', 'id' => $referal_url->user_id]);
        } else {

            return $this->render('edit-ref-url', [
                'referal_url' => $referal_url,
                'user_id' => $referal_url->user_id
            ]);
        }
    }

    public function actionViewDetails($id)
    {
        $ref_details = ReferalsDetail::find()->where(['id_ref' => $id]);
        return $this->render('view-details', [
            'ref_details' => $ref_details,
            'id_ref' => $id
        ]);
    }

    public function actionCreateRefDet($id)
    {
        $ref_det = new ReferalsDetail();

        if ($ref_det->load(Yii::$app->request->post()) && $ref_det->save()) {
            return $this->redirect(['view-details', 'id' => $ref_det->id_ref]);
        } else {
            return $this->render('create-ref-det', [
                'ref_det' => $ref_det,
                'id_ref' => $id
            ]);
        }
    }

    public function actionUpdateRefDet($id)
    {
        $ref_det = ReferalsDetail::findOne($id);

        if ($ref_det->load(Yii::$app->request->post()) && $ref_det->save()) {
            return $this->redirect(['view-details', 'id' => $ref_det->id_ref]);
        } else {

            return $this->render('update-ref-det', [
                'ref_det' => $ref_det,
                'id_ref' => $ref_det->id_ref
            ]);
        }
    }

    public function actionDelRefDet($id)
    {
        ReferalsDetail::findOne($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        ReferalsUrl::findOne($id)->delete();
        return $this->redirect(['index']);
    }
}