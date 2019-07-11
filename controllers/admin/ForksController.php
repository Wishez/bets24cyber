<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Fork;
use app\models\LoadData;
use yii\db\Query;

/**
 * StreamsController implements the CRUD actions for TopStreams model.
 */
class ForksController extends Controller
{
    public $layout = "admin";
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TopStreams models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new BookmakerSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
        $post = Yii::$app->request->post();
        if(!empty($post)){
            $sql = 'DELETE FROM fork_email; ';
            foreach ($post['tags'] as $tag) {
                $sql .= LoadData::insert('fork_email', ['email' => $tag]);

            }
            Yii::$app->db->createCommand($sql)->execute();
                    }
        $model = Fork::find()->select('f.*, d1.link as link1, d2.link as link2')->alias('f')->leftJoin('bk_desc d1', 'd1.type = f.bk1')->leftJoin('bk_desc d2', 'd2.type = f.bk2')->orderBy('f.date DESC');
        return $this->render('index', ['model' => $model]);
    }


}
