<?php

namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * StreamsController implements the CRUD actions for TopStreams model.
 */
class TagsController extends Controller
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
    $tag = Yii::$app->request->post("tag");
        if(!empty($tag) && isset($tag)){
            Yii::$app->db->createCommand('INSERT IGNORE INTO tags VALUES("", "'.strtolower($tag).'")')->execute();

        }
        $model = Yii::$app->db->createCommand('
            SELECT tag 
            FROM tags     
            ORDER BY lower(tag)')->queryAll();
        return $this->render('index', ['model' => $model]);
    }
    public function actionFind(){
        $part = Yii::$app->request->get("part");
        if(!empty($part) && isset($part)){
            $find = Yii::$app->db->createCommand('SELECT a.tag 
                FROM ( 
                SELECT tag FROM tags WHERE tag is not NULL 
                UNION 
                SELECT name as tag FROM teams WHERE name is not NULL 
                UNION  
                SELECT player_id as tag FROM team_players WHERE player_id is not NULL 
                UNION SELECT name as tag FROM leagues WHERE name is not NULL) a WHERE lower(a.tag) LIKE "'.$part.'%" LIMIT 40')->queryAll();
            echo json_encode($find);
        }else{
            echo 0;
        }
    }


}
