<?php

namespace app\controllers;

use \Exception;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\models\Orders;
use app\models\PayOrder;

use app\models\DotaGame;
use app\models\League;
use app\models\LoadTeam;
use app\models\Match;
use app\models\Settings;

use app\models\ResetPasswordForm;
use app\models\SendEmailForm;
use app\models\SignupForm;
use app\models\StaticPage;

use app\models\User;
use app\models\DotaHelper;
use nodge\eauth\openid\ControllerBehavior;
use app\models\TwitchLeagues;
use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['api', 'news', 'match-info', 'pay-order', 'add-order', 'league-info', 'search', 'match-details', 'league-details', 'stream', 'send-email', 'reset-password', 'teams', 'team-info'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],

                ],
            ],
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => ControllerBehavior::className(),
                'only' => ['login'],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actions()
    {
        $timezones = [];
        foreach (Settings::getTimezones() as $key => $zone) {
            list(, $name) = explode(') ', $key);
            $timezones[] = [
                'name'=>$name,
                'tz'=>$zone
            ];
        }
        $this->view->params['timezones'] = json_encode($timezones);
        $this->view->params['default_timezone'] = Settings::getTimezones()[Settings::getValues()["MAIN_SITE_TIMEZONE"]];
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor' => 0x091011,
                'foreColor' => 0xb83800,
            ],
        ];
    }




    public function actionAddOrder()
    {
        $model =  new Orders();
        $response = [];

        $post['Orders'] = Yii::$app->request->post();
        if ($model->load($post)) {
            $match = Match::findOne($model->match_id);
            if (Yii::$app->user->isGuest) {
                $response['message'] = 'Ваше предложенная ставка не была принята. Чтобы её принять, авторизуйтесь, либо пройдите регистрацию.';
                $response['error'] = 2;
                return json_encode($response);
            }
            if ($match->gameOver) {
                $response['message'] = 'Матч завершился. Попробуйте поставить ставку на другой матч';
                $response['error'] = 1;
                return json_encode($response);
            }

            if ((strtotime($match->date)+($match->orders_time*60)) <= time()||!$match->orders_active) {
                $response['message'] = 'В данный моммент прием ставок закрыт';
                $response['error'] = 1;
                return json_encode($response);
            }

            if ($model->add()) {
                $response['message'] = 'Ваша ставка принята';
                $response['error'] = 0;
                $response['order_id'] = $model->id;
                return json_encode($response);
            } else {
                foreach ($model->errors as $key => $error) {
                    $response['message'] = $error[0];
                    $response['error'] = 1;
                    return json_encode($response);
                }
                //var_dump();
            }
            exit();
        }
    }

    public function actionRepayOrder()
    {
        $model =  new PayOrder();
        $post['PayOrder'] = Yii::$app->request->post();
        if ($model->load($post)) {
            if ($repay = $model->repay()) {
                return json_encode($repay);
            } else {
                foreach ($model->errors as $key => $error) {
                    $response['message'] = $error[0];
                    $response['error'] = 1;
                    return json_encode($response);
                }
            }
            exit();
        }
    }

    public function actionCheckOrders()
    {
        $orders = Orders::find()->where(['status'=>[Orders::STATUS_OPEN, Orders::STATUS_CLOSED]])->with('match')->all();
        // var_dump($orders);
        // exit();
        foreach ($orders as $key => $order) {
            // var_dump($order->match->date);
            // var_dump(strtotime($order->match->date) <= time()&&Orders::STATUS_OPEN);
            //exit();
            if ((strtotime($order->match->date)+($order->match->orders_time*60) <= time()&&$order->status==Orders::STATUS_OPEN)||!$order->match->orders_active) {
                if ($order->payersTransactionsSum>0) {
                    $order->status = Orders::STATUS_CLOSED;
                    $order->save(false);
                } else {
                    $order->disable();
                }
            } elseif ($order->match->gameOver&&$order->status==Orders::STATUS_CLOSED) {
                $order->processing();
            }
        }
        //var_dump($orders);
    }

    public function actionAjaxValidate($mod)
    {
        $class = "\app\models\\".$mod;
        $model =  new $class;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            \Yii::$app->end();
        }
    }

    public function actionPayOrder()
    {
        $id = Yii::$app->request->post('order_id');

        $order = Orders::findOne($id);
        // var_dump($id);
        // exit();
        $model = new PayOrder();
        $model->order_id = $id;
        $status = $model->pay();
        if ($status) {
            if ($status==1) {
                $response['message'] = 'Ваш ордер успешно оплачен';
                $response['error'] = 0;
                $response['url'] = Yii::$app->urlManager->createUrl(['site/match-details', 'id'=>$order->match->id]);
                $response['balance'] = Yii::$app->user->identity->balance;
                return json_encode($response);
            } elseif ($status==3) {
                $response['message'] = 'У Вас не хватает денег на счету.';
                $response['error'] = 1;
                //$response['url'] = Yii::$app->urlManager->createUrl(['site/match-details', 'id'=>$order->match->id]);
                return json_encode($response);
            }
        }
    }

    public function actionPlainOrder()
    {
        return 'Ваш ордер запланирован!';
    }

    public function actionMatchDetails()
    {
        $id = intval(Yii::$app->request->get("id"));

        if (!empty($id)) {
            $orderModel =  new Orders();
            $payorderModel =  new PayOrder();
            $orders =  Orders::find()->where(['status'=>1, 'match_id'=>$id])->all();
            $match = Match::allMatches()->where(['m.id' => $id])->one();


            $j_orders = [];
            foreach ($orders as $key => $order) {
                $j_orders[$order->winner][] = [
                    'bet'=>[
                        'id'=>$order->id
                    ],
                    'offered'=>[
                        'bet'=>$order->summ,
                        'ratio'=>$order->rate,
                        'win'=>($order->summ*$order->rate)-$order->summ,
                    ],
                    'accepted'=>[
                        'bet'=>($order->summ*$order->rate)-$order->summ-(int)$order->payersTransactionsSum,
                        'ratio'=>round((($order->summ*$order->rate))/(($order->summ*$order->rate)-$order->summ), 2),
                    ],
                ];
            }


            if ($match) {
                $idArr = [];
                $id = $match->league_id;
                $idArr[]=$id;
                $f = true;
                do {
                    $getLeagues = League::find()->select('id')
                        ->leftJoin('league_quals', '`leagues`.`league_id`= league_quals.league_id')
                        ->where(['qual_id'=>$id])->asArray()->one();
                    if ($getLeagues) {
                        $id = $getLeagues['id'];
                        $idArr[]=$id;
                    } else {
                        $f = false;
                    }
                } while ($f);
                $streamArr = [];
                $twitchLeagues = TwitchLeagues::find()->where(['IN','leagues_id',$idArr])->orderBy(['is_main'=>SORT_DESC, 'id'=>SORT_DESC])->all();
                foreach ($twitchLeagues as $twitch) {
                    if (array_search(strtolower($twitch->channel), $streamArr) !== false) {
                        continue;
                    }
                    $streamArr[] = $twitch->channel;
                }

                foreach ($match->twitch as $twitch) {
                    if (array_search($twitch->channel, $streamArr) !== false) {
                        continue;
                    }
                    $streamArr[] = $twitch->channel;
                }
                $streams_js = str_replace('"', "'", json_encode($streamArr));



                return $this->renderAjax(
                    'match-info',
                [
                    'match' => $match,
                    'j_orders_first' => str_replace('"', "'", json_encode($j_orders[1])),
                    'j_orders_two' => str_replace('"', "'", json_encode($j_orders[2])),
                    'j_orders_draw' => str_replace('"', "'", json_encode($j_orders[0])),
                    'streams_js'=>$streams_js,
                    'orderModel' => $orderModel,
                    'payorderModel'=>$payorderModel
                ]
                );
            }
        }
        //return $this->redirect(['site/index']);
    }


    public function actionSubcat()
    {
        var_dump(Yii::$app->user->identity);
        exit();
    }





    public function actionIndex($dm_game=false, $am_game=false)
    {
        $s = time();
        $this->view->params['class_page'] = 'home';
        $index = [];
        $post = Yii::$app->request->post();
        $page = 1;
        
        $matches = Match::getActiveMatches()->all();
        
        $index['all'] = [];
        $index['today'] = [];
        $index['leagues'] = [
            '0' =>[
                'id' =>  0,
                'name'=> 'Турнир',
            ]
        ];

        foreach ($matches as $match) {
            if ($match->gameOver == 0) {
                if ($am_game===false||$match->game==$am_game) {
                    if (strtotime($match->date) <= time() && $match->gameOver == 0) {
                        $match->active = 'true';
                    } else {
                        $match->active = 'false';
                    }
                    $tr = true;
                    if ($post) {
                        // $date = date_create($post['sinceDate']);
                        // var_dump($date);
                        if ($post['sinceDate']&&date('Y-m-d H:i:s', strtotime($post['sinceDate']))>=$match->date) {
                            $tr = false;
                        }
                        if ($post['untilDate']&&date('Y-m-d H:i:s', strtotime($post['untilDate']))<=$match->date) {
                            $tr = false;
                        }
                        if ($post['type']&&$post['type']['id']>0&&$post['type']['id']!=$match->league_id) {
                            $tr = false;
                        }

                        if ($post['search']&&strpos($match->teamOne->name, $post['search'])===false&&strpos($match->teamTwo->name, $post['search'])===false) {
                            $tr = false;
                        }
                    }
                    if ($tr) {
                        array_push($index['all'], $match);
                    }

                    
                    if (!isset($index['leagues'][$match->league_id])) {
                        $index['leagues'][$match->league_id] = [
                            'id' =>  $match->league_id,
                            'name'=> $match->league->name,
                        ];
                    }
                }

                if ($match->date>=date('Y-m-d 00:00:00')) {
                    if ($dm_game===false||$match->game==$dm_game) {
                        array_push($index['today'], $match);
                    }
                }
            }
        }


        $total = count($index['all']); //total items in array
        $limit = 20; //per page
        $totalPages = ceil($total/ $limit); //calculate total pages
        $offset = 0;

        if ($post['page']>1) {
            $limit = $limit*$post['page'];
            $page = $post['page'];
        }
        $seil  = $totalPages-$page ;
        $index['all'] = array_slice($index['all'], $offset, $limit);
        
        return $this->render('index', [
            'news' => $news,
            'model' => $index,
            'next_page' =>$page+1,
            'seil'=>$seil,
            'page'=>$page


        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::previous());
        }

        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {


            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
                    $identity = User::findByEAuth($eauth);
                    if (!$identity || !Yii::$app->getUser()->login($identity)) {
                        Yii::$app->getSession()->setFlash('error', 'Извините произошла ошибка');
                        $eauth->redirect($eauth->getCancelUrl());
                    }
                    $eauth->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());

                // close popup window and redirect to cancelUrl
                $eauth->redirect($eauth->getCancelUrl());
            }
        }




        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        // var_dump($model->errors);
        // exit();
        if ($model->login&&$model->password) {
            if ($model->login()) {
                return 'ok';
            } else {
                return 'no';
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionAuth()
    {
        if (Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->login()) {
                    echo 1;
                } else {
                    if ($model->hasErrors()) {
                        $err = $model->getErrors();
                        if (isset($err['password'])) {
                            echo $err['password'][0];
                        }
                    } else {
                        echo 'Ошибка';
                    }
                }
            } else {
                echo 'Ошибка';
            }
        } else {
            echo 'Ошибка';
        }
    }
    public function actionReg()
    {
        if (Yii::$app->user->isGuest) {
            $model = new SignupForm();

            $post = Yii::$app->request->post();
            $fields = UserFields::find()->where(['require_signup' => 1])->all();
            $values = [];
            foreach ($fields as $field) {
                if (empty($post['SignupForm'][$field->column])) {
                    return 'Заполните '.$field->name;
                } else {
                    $values[$field->column] = $post['SignupForm'][$field->column];
                }
            }

            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup($values)) {
                    return 1;
                } else {
                    if ($model->hasErrors()) {
                        $err = $model->getErrors();
                        foreach ($err as $key => $value) {
                            echo $value[0];
                            break;
                        }
                    }
                }
            } else {
                echo 'Ошибка';
            }
        } else {
            echo 'Ошибка';
        }
    }
    public function actionActivateUser()
    {
        $id = Yii::$app->request->get('id');
        $key = Yii::$app->request->get('key');
        $active = new SignupForm();

        $active->activateUser($id, $key);
        $this->redirect('/');
    }
    public function actionSignup()
    {
        $req = ['SignupForm'=>Yii::$app->request->post()];
        $model = new SignupForm();
        if ($model->load($req)) {
            if ($user = $model->signup()) {
                if (Yii::$app->user->login($user)) {
                    return 'ok';
                }
            } else {
                foreach ($model->errors as $key => $error) {
                    return $error[0];
                }
            }
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }



    public function actionSendEmail()
    {
        $model = new SendEmailForm();
        $req = ['SendEmailForm'=>Yii::$app->request->post()];
        if ($model->load($req)) {
            if ($model->validate()) {
                $model->sendEmail();
                return 'ok';
            } else {
                foreach ($model->errors as $key => $error) {
                    return $error[0];
                }
            }
        }
        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try {
            $model = new ResetPasswordForm($key);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/site/login']);
            }
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
