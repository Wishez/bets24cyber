<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\lang;
use app\models\LoadData;
use yii\db\Query;

/**
 * StreamsController implements the CRUD actions for TopStreams model.
 */
class LangsController extends Controller
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
                    'delete' => ['get'],
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
        $langs = Lang::find()->orderBy([
          'id'=>SORT_DESC
        ])->all();

        return $this->render('langs', [
          'langs' => $langs,
        ]);
    }


    public function actionDelete($id)
    {
        $model = Lang::findOne($id);
        if ($model->delete()) {
        }
        return Yii::$app->response->redirect(['admin/langs']);
    }

    public function actionAdd()
    {
        $model = new Lang();
        $pages = [
             'main'=>'Главная',
            'profile'=>'Профиль',

        ];

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (!file_exists(Yii::getAlias('@app').'/messages/'.$model->url)) {
                    mkdir(Yii::getAlias('@app').'/messages/'.$model->url);
                }
                foreach ($pages as $key => $value) {
                    if (file_exists(Yii::getAlias('@app').'/messages/en/'.$key.'.php')) {
                        copy(Yii::getAlias('@app').'/messages/en/'.$key.'.php', Yii::getAlias('@app').'/messages/'.$model->url.'/'.$key.'.php');
                    }
                }
                return Yii::$app->response->redirect(['admin/langs/index']);
            } else {
                return $this->render('add', [
                'model' => $model,




                ]);
            }
        } else {
            return $this->render('add', [
            'model' => $model,



            ]);
        }
        // var_dump($model->errors);
        // exit();
    }

    public function actionEdit($id)
    {
        $model = Lang::findOne($id);
        $pages = [
            'main'=>'Главная',
            'profile'=>'Профиль',

        ];
        foreach ($pages as $key => $value) {
            $trans[$key] = array(''=>'');
            if (file_exists(Yii::getAlias('@app').'/messages/'.$model->url.'/'.$key.'.php')) {
                $trans[$key] = include_once Yii::getAlias('@app').'/messages/'.$model->url.'/'.$key.'.php';
            }
        }



        if (Yii::$app->request->post('trans')) {
            if (!file_exists(Yii::getAlias('@app').'/messages/'.$model->url)) {
                mkdir(Yii::getAlias('@app').'/messages/'.$model->url);
            }

            $trans_post = Yii::$app->request->post('trans');
            foreach ($pages as $k => $v) {
                $insert = '<? return [';
                if (isset($trans_post[$k])) {
                    foreach ($trans_post[$k]['key'] as $key => $value) {
                        $insert .= '"'.$value.'"=>"'.$trans_post[$k]['val'][$key].'",'.PHP_EOL;
                    }
                    $insert .= '];';
                    file_put_contents(Yii::getAlias('@app').'/messages/'.$model->url.'/'.$k.'.php', $insert);
                }
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                return Yii::$app->response->redirect(['admin/langs/index']);
            } else {
                return $this->render('edit', [
                'model' => $model,
                'trans' => $trans,
                'pages'=>$pages




                ]);
            }
        } else {
            return $this->render('edit', [
            'model' => $model,
            'trans' => $trans,
            'pages'=>$pages



            ]);
        }
        // var_dump($model->errors);
        // exit();
    }
}
