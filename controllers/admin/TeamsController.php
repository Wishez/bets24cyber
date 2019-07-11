<?php

namespace app\controllers\admin;

use app\models\TopStreams;
use app\models\UploadForm;
use Yii;
use app\models\Team;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\NewsSearch;
use app\models\LoadData;
use app\models\LoadTeam;
/**
 * NewsController implements the CRUD actions for News model.
 */
class TeamsController extends Controller
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => News::find(),
//        ]);
        $get = Yii::$app->request->get();
        $model = Team::find()->orderBy('game DESC, earning DESC');
        if(!empty($get['query'])){
            $model->where('name LIKE "%'.$get['query'].'%"');
        }   
        

        return $this->render('index', ['model' => $model]);
    }


    public function actionEdit($id){
        $model = Team::find()->where(['id' => $id])->one();
        $post = Yii::$app->request->post();
        if($post){

        //print_r($post);
        //return;

            $model->attributes = Yii::$app->request->post('Team');
            $model->save();
                $sql = 'DELETE FROM team_names WHERE team_id = "'.$id.'"; ';
                if(!empty($post['names'])){
                    foreach ($post['names'] as $name) {
                        $sql .= LoadData::insert('team_names', ['team_id' => $id, 'name' => $name]);
                    }
                }
                if(!empty($post['text'])){
                    //print_r($post['text']);
                    //return;
                    $sql .= 'DELETE FROM teams_players_about WHERE id = "'.$model->team_id.'" AND game = "'.$model->game.'"; ';
                    foreach ($post['text'] as $text) {
                        $sql .= LoadData::insert('teams_players_about', 
                            ['id' => $model->team_id, 'game' => $model->game, 'text' => htmlspecialchars($text['text']), 'name' => $text['name'], 'sort' => $text['sort']]);
                    }
                }
                Yii::$app->db->createCommand($sql)->execute();

            return $this->redirect('/admin/teams');
        }
            
            return $this->render('edit', ['model' => $model]);
        
        
        
    }
    public function actionUpdate($id){
        $team = Team::find()->where(['id' => $id])->one();
        $f = new LoadTeam($team->team_id, $team->game);


    }

}
