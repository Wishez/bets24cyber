<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Match;
use app\models\LoadData;
/**
 * StreamsController implements the CRUD actions for TopStreams model.
 */
class MatchesController extends Controller
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
        $query = Yii::$app->request->get('query');
        $model = Match::find()->orderBy('date DESC')->where(['active' => 1])->with('league');
        
        if(!empty($query)){
            $model = $model->andWhere('team1 LIKE "%'.$query.'%" OR team2 LIKE "%'.$query.'%"');
        }
        
        return $this->render('index', ['model' => $model]);
    }
    public function actionEdit($id)
    {
        $model = Match::findOne($id);
        $post = Yii::$app->request->post();

        if(!empty($post)){
            $model->attributes = Yii::$app->request->post('Match');
            $model->save();
            
            if(!empty($post['streams'])){
                $sql = 'DELETE FROM twitch WHERE match_id = "'.$id.'"; ';
                foreach ($post['streams'] as $stream) {
                    $sort = 7;
                    if(!empty($stream['main'])){
                        $sort = 1;
                    }
                    if(strtolower($stream['country']) == 'ru'){
                        $sort = 6;
                    }
                    $sql .= LoadData::insert('twitch', ['match_id' => $id, 'channel' => $stream['channel'], 'country' =>  $stream['country'], 'sort' => $sort]);
                    
                }
                Yii::$app->db->createCommand($sql)->execute();
        }
            
            return $this->redirect('/admin/matches');


        }
        
        return $this->render('edit', ['model' => $model]);
    }


}
