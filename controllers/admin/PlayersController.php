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
use app\models\LoadPlayer;
use app\models\Player;
/**
 * NewsController implements the CRUD actions for News model.
 */
class PlayersController extends Controller
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
        $model = Player::find()->orderBy('game DESC, earning DESC')->with('team');
        if(!empty($get['query'])){
            $model->where('player_id LIKE "%'.$get['query'].'%" OR team_id LIKE "%'.$get['query'].'%"');
        }   
        

        return $this->render('index', ['model' => $model]);
    }


    public function actionEdit($id){
        $model = Player::find()->where(['id' => $id])->one();
        $post = Yii::$app->request->post();
        if($post){

        //print_r($post);
        //return;

            $model->attributes = Yii::$app->request->post('Player');
            $model->save();
                $sql = '';
                if(!empty($post['text'])){
                    //print_r($post['text']);
                    //return;
                    $sql .= 'DELETE FROM teams_players_about WHERE id = "'.$model->player_id.'" AND game = "'.$model->game.'"; ';
                    foreach ($post['text'] as $text) {
                        $sql .= LoadData::insert('teams_players_about', 
                            ['id' => $model->player_id, 'game' => $model->game, 'text' => htmlspecialchars($text['text']), 'name' => $text['name'], 'sort' => $text['sort']]);
                    }
                }
                Yii::$app->db->createCommand($sql)->execute();

            return $this->redirect('/admin/players');
        }
            
            return $this->render('edit', ['model' => $model]);
        
        
        
    }
    public function actionUpdate($id){
        $player = Player::find()->where(['id' => $id])->one();
        
        $loadPlayer = new LoadPlayer($player->team_id, $player->player_id, $player->game);
        $loadPlayer->overview();

        $player->attributes = $loadPlayer->getData();

        $player->save();
        Yii::$app->db->createCommand($loadPlayer->getSql())->execute();  

    }

}
