<?php

namespace app\controllers\admin;

use app\models\Bookmaker;
use app\models\BookmakerRate;
use app\models\Match;
use app\models\MatchCsgoSearch;
use app\models\SaveLeague;
use app\models\Team;
use Yii;
use app\models\GetLeagueData;
use app\models\LoadLeague;
use app\models\LoadData;
use app\models\League;
use app\models\TeamData;
use app\models\Leaguedata;
use app\models\LeagueCsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \Exception;
use app\models\LoadTeam;

/**
 * LeagueCsController implements the CRUD actions for League model.
 */
class LeaguesController extends Controller
{
    public $layout = "admin";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        /*
                        'actions' => ['index','create-csj', 'change-match','delta','add-bookmaker','del-boomark',
                                      'create','view','show-game', 'show-league','show-book-offer','delete'],
                        'allow' => true,
                        'roles' => ['admin'],*/
                        'allow' => true,
                        // 'roles' => ['admin'],
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
     * Lists all League models.
     * @return mixed
     */

    public function actionEnable($id)
    {
        $league = League::findOne($id);
        $league->close = 0;
        $league->save(false);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionDisable($id)
    {
        $league = League::findOne($id);
        $league->close = 1;
        $league->save(false);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionIndex()
    {
        // if (!\Yii::$app->user->can('просмотр раздела транзакции')) {
        //     throw new ForbiddenHttpException('Access denied');
        // }
        // $searchModel = new LeagueCsSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->);

        $model_active = League::find()->where(['event' => 0])->andWhere('date_end - INTERVAL 1 DAY >= NOW()');
        if (Yii::$app->request->get('search')) {
            $model_active = $model_active->andWhere(['like','name', Yii::$app->request->get('search')]);
        }
        $model_active = $model_active->orderBy('date_start DESC');
        $model_last = League::find()->where(['event' => 0])->andWhere('date_end + INTERVAL 1 DAY < NOW()')->orderBy('date_start DESC');
        if (Yii::$app->request->get('search')) {
            $model_last = $model_last->andWhere(['like','name', Yii::$app->request->get('search')]);
        }
        $model_last = $model_last->orderBy('date_start DESC');
        return $this->render('index', ['model_active' => $model_active, 'model_last' => $model_last, 'search'=>Yii::$app->request->get('search')]);
    }
}
