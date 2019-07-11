<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Match;
use app\models\LoadData;
use yii\db\Query;

/**
 * StreamsController implements the CRUD actions for TopStreams model.
 */
class BookmakerController extends Controller
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
        $model = (new Query)->select('*')->from('bk_desc')->all();
        return $this->render('index', ['model' => $model, 'work' => file_get_contents(Yii::getAlias('@app').'/bk.work')]);
    }
    public function actionUpdate(){
        $work = file_get_contents(Yii::getAlias('@app').'/bk.work');
        $w = 1;
        if($work == 1){
            $w = 0;
        }
        file_put_contents(Yii::getAlias('@app').'/bk.work', $w);
        return $this->redirect('/admin/bookmaker');

    }
    public function actionEdit($id)
    {

        $model = (new Query)->select('*')->from('bk_desc')->where(['id' => $id])->one();
        $post = Yii::$app->request->post();
        if(!empty($post)){
            $sql = '';
            if($_FILES['img']['size'] != 0){
                $img = '/img/'.basename($_FILES['img']['name']);
                move_uploaded_file($_FILES['img']['tmp_name'], Yii::getAlias('@webroot').$img);
                $sql = ', img = "'.$img.'"';
            }

            Yii::$app->db->createCommand('UPDATE bk_desc SET link = "'.$post['link'].'" '.$sql.' WHERE id = "'.$id.'"')->execute();
            return $this->redirect('/admin/bookmaker');
        }
        return $this->render('edit', ['model' => $model]);
    }


}
