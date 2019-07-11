<?php

namespace app\controllers;

use app\models\ReferalsDetail;
use app\models\ReferalsUrl;
use app\models\ReferelWallets;
use app\models\SettingsUser;
use app\models\User;
use app\models\Orders;
use app\models\Transaction;
use app\models\PayOrder;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class UserController extends \yii\web\Controller
{
    public $layout = "LK";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);
        $raiting = 0;
        $avatar = $user->avatar;
        $birthday = $user->birthday;
//        var_dump("<pre>",$posts);die();
        return $this->render('index', ['user' => $user, 'raiting' => $raiting, 'birthday' => $birthday, 'avatar' => $avatar]);
    }

    public function actionPosts()
    {
        $user = User::findOne(Yii::$app->user->id);
        $posts = $user->getUserPost();
//        var_dump("<pre>",$posts);die();
        $postDataProvider = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => ['pageSize' => 10,]
        ]);
        return $this->render('post-list', ['postDataProvider' => $postDataProvider]);
    }

    public function actionTransactions()
    {
        $user = User::findOne(Yii::$app->user->id);
        $data = Yii::$app->request->post('data');
        $rows = [];
        foreach ($user->myTransactionsIn as $key => $order) {
            $row=[];
            $row['date'] = Yii::$app->formatter->asDate($order->date, 'php:m.d.Y H:i');
            $row['action'] = 'Депозит на виртуальный счёт';
            $row['status']['text'] = $order->statusText;
            $row['status']['processed'] = $order->status == 0 ? true : false;
            $row['sum'] = $order->amount;
            $row['commission'] = $order->commision;
            $row['rest'] = $user->balance;
            if ($data) {
                // $date = date_create($post['sinceDate']);
                // var_dump($date);
                if ($data['actionType']&&isset($data['actionType']['id'])&&$data['actionType']['id']>0&&$data['actionType']['id']!=1) {
                    $tr = false;
                }
                if ($data['sinceDate']&&date('Y-m-d H:i:s', strtotime($post['sinceDate']))>=$order->date) {
                    $tr = false;
                }
                if ($post['untilDate']&&date('Y-m-d H:i:s', strtotime($post['untilDate']))<=$order->date) {
                    $data = false;
                }
                if ($data['status']&&isset($data['status']['id'])&&$data['status']['id']>0&&$data['status']['id']!=($order->status+1)) {
                    $tr = false;
                }
            }
            if ($tr) {
                $rows[] =  $row;
            }
            unset($row);
        }


        foreach ($user->myTransactionsOut as $key => $order) {
            $row=[];
            $row['date'] = Yii::$app->formatter->asDate($order->date, 'php:m.d.Y H:i');
            $row['action'] = 'Вывод средств на виртуальный счёт';
            $row['status']['text'] = $order->statusText;
            $row['status']['processed'] = $order->status == 1 ? true : false;
            $row['sum'] = $order->amount;
            $row['commission'] = $order->commision;
            $row['rest'] = $user->balance;
            if ($data) {
                // $date = date_create($post['sinceDate']);
                // var_dump($date);
                if ($data['actionType']&&isset($data['actionType']['id'])&&$data['actionType']['id']>0&&$data['actionType']['id']!=2) {
                    $tr = false;
                }
                if ($data['sinceDate']&&date('Y-m-d H:i:s', strtotime($post['sinceDate']))>=$order->date) {
                    $tr = false;
                }
                if ($post['untilDate']&&date('Y-m-d H:i:s', strtotime($post['untilDate']))<=$order->date) {
                    $data = false;
                }
                if ($data['status']&&isset($data['status']['id'])&&$data['status']['id']>0&&$data['status']['id']!=($order->status+1)) {
                    $tr = false;
                }
            }
            if ($tr) {
                $rows[] =  $row;
            }
            unset($row);
        }
        return json_encode($rows);
    }

    public function actionOrders()
    {
        $user = User::findOne(Yii::$app->user->id);
        $posts = $user->orders;
        $data = Yii::$app->request->post('data');

        $rows = [];
        foreach ($posts as $key => $order) {
            $row=[];

            $add_status = '';
            if ($order->match->gameOver&&$order->status==0) {
                $add_status = 'Отменен';
            } elseif ($order->status==1&&$order->payersTransactionsSum) {
                $add_status = 'Ожидание матча';
            } elseif ($order->status==2) {
                $add_status = 'Ожидание результата';
            } elseif ($order->status==3) {
                if ($order->payScore>$order->orderScore) {
                    $add_status = 'Проигрыш';
                } else {
                    $add_status = 'Победа';
                }
            }

            $row['bet']['id'] = $order->id;
            $row['bet']['status'] = $order->statusName;

            $row['bet']['ratio'] = $order->rate;
            $row['bet']['sum'] = $order->summ;
            $row['bet']['acceptedSum'] = $order->payersTransactionsSum;
            $row['game']['image'] ='/img/game_'.$order->match->league->game.'.png';
            $row['match']['date']=Yii::$app->formatter->asDate($order->match->date, 'php:m/d/Y H:i');
            $row['match']['noteAboutEndMatch'] = $add_status;


            $row['leftTeam']['logo'] = 'https://24cyber.ru'.$order->match->teamOne->logo;
            $row['leftTeam']['name'] = $order->match->teamOne->name;
            $row['rightTeam']['logo'] = 'https://24cyber.ru'.$order->match->teamTwo->logo;
            $row['rightTeam']['name'] = $order->match->teamTwo->name;
            $row['winTeam']['logo'] = 'https://24cyber.ru'.$order->orderTeam->logo;
            $row['winTeam']['name'] = $order->orderTeam->name;
            $row['buttonsDisplay']['accept'] = $order->status==0&&!$order->match->gameOver;
            $row['buttonsDisplay']['cancel'] = $order->status==1&&!$order->payersTransactionsSum;
            $row['buttonsDisplay']['delete'] = $order->status==0&&!$order->match->gameOver;
            $tr = true;
            if ($data) {
                // $date = date_create($post['sinceDate']);
                // var_dump($date);
                if ($data['status']&&isset($data['status']['id'])&&$data['status']['id']>0&&$data['status']['id']!=($order->status+1)) {
                    $tr = false;
                }
                if ($data['sinceDate']&&date('Y-m-d H:i:s', strtotime($post['sinceDate']))>=$order->match->date) {
                    $tr = false;
                }
                if ($post['untilDate']&&date('Y-m-d H:i:s', strtotime($post['untilDate']))<=$order->match->date) {
                    $data = false;
                }
                if ($data['game']&&isset($data['game']['id'])&&$data['game']['id']>0&&$data['game']['id']!=($order->match->league->game+1)) {
                    $tr = false;
                }
            }
            if ($tr) {
                $rows[] =  $row;
            }
            unset($row);
        }
        return json_encode($rows);
    }

    public function actionPays()
    {
        $user = User::findOne(Yii::$app->user->id);
        $posts = $user->getPays();
        $data = Yii::$app->request->post('data');

        $rows = [];
        foreach ($posts->all() as $key => $order) {
            $row=[];

            $add_status = '';
            if ($order->order->match->gameOver==1&&!$order->order->getPays()->where(['transactions.status'=>0])->count()) {
                if ($order->order->payScore>$order->order->orderScore) {
                    $add_status = 'Победа';
                } else {
                    $add_status = 'Проигрыш';
                }
            } else {
                $add_status = 'В процессе';
            }

            $row['bet']['id'] = $order->id;
            $row['bet']['status'] = $order->payText;

            $row['bet']['ratio'] = (($order->order->summ*$order->order->rate))/(($order->order->summ*$order->order->rate)-$order->order->summ);
            $row['bet']['sum'] = $order->amount;
            $row['bet']['acceptedSum'] = false;
            $row['game']['image'] ='/img/game_'.$order->order->match->league->game.'.png';
            $row['match']['date']=Yii::$app->formatter->asDate($order->order->match->date, 'php:m/d/Y H:i');
            $row['match']['noteAboutEndMatch'] = $add_status;


            $row['leftTeam']['logo'] = 'https://24cyber.ru'.$order->order->match->teamOne->logo;
            $row['leftTeam']['name'] = $order->order->match->teamOne->name;
            $row['rightTeam']['logo'] = 'https://24cyber.ru'.$order->order->match->teamTwo->logo;
            $row['rightTeam']['name'] = $order->order->match->teamTwo->name;
            $row['winTeam']['logo'] = 'https://24cyber.ru'.$order->order->payTeam->logo;
            $row['winTeam']['name'] = $order->order->payTeam->name;
            $row['buttonsDisplay']['accept'] = !$order->order->match->gameOver&&$order->status==0;
            $row['buttonsDisplay']['cancel'] = false;
            $row['buttonsDisplay']['delete'] = $order->status==0;
            
            $tr = true;
            if ($data) {
                // $date = date_create($post['sinceDate']);
                // var_dump($date);
                if ($data['status']&&isset($data['status']['id'])&&$data['status']['id']>0&&$data['status']['id']!=($order->status+1)) {
                    $tr = false;
                }
                if ($data['sinceDate']&&date('Y-m-d H:i:s', strtotime($post['sinceDate']))>=$order->order->match->date) {
                    $tr = false;
                }
                if ($post['untilDate']&&date('Y-m-d H:i:s', strtotime($post['untilDate']))<=$order->order->match->date) {
                    $data = false;
                }
                if ($data['game']&&isset($data['game']['id'])&&$data['game']['id']>0&&$data['game']['id']!=($order->order->match->league->game+1)) {
                    $tr = false;
                }
            }
            if ($tr) {
                $rows[] =  $row;
            }
            unset($row);
        }
        return json_encode($rows);
    }


    public function actionData()
    {
        $user = User::findOne(Yii::$app->user->id);
        $data=[];
        $data['newUserTransactionsData']=[];
        $data['newUserCreatedBets']=[];
        $data['newUserContactCenterData']=[];
        $data['newUserAcceptedBets']=[];

        //pays
        foreach ($user->pays as $key => $order) {
            if ($order->order->rate==1||$order->order->summ==0) {
                continue;
            }
            $row=[];

            $add_status = '';
            if ($order->order->match->gameOver==1&&!$order->order->getPays()->where(['transactions.status'=>0])->count()) {
                if ($order->order->payScore>$order->order->orderScore) {
                    $add_status = 'Победа';
                } else {
                    $add_status = 'Проигрыш';
                }
            } else {
                $add_status = 'В процессе';
            }

            $row['bet']['id'] = $order->id;
            $row['bet']['status'] = $order->payText;

            $row['bet']['ratio'] = (($order->order->summ*$order->order->rate))/(($order->order->summ*$order->order->rate)-$order->order->summ);
            $row['bet']['sum'] = $order->amount;
            $row['bet']['acceptedSum'] = false;
            $row['game']['image'] ='/img/game_'.$order->order->match->league->game.'.png';
            $row['match']['date']=Yii::$app->formatter->asDate($order->order->match->date, 'php:m/d/Y H:i');
            $row['match']['noteAboutEndMatch'] = $add_status;


            $row['leftTeam']['logo'] = 'https://24cyber.ru'.$order->order->match->teamOne->logo;
            $row['leftTeam']['name'] = $order->order->match->teamOne->name;
            $row['rightTeam']['logo'] = 'https://24cyber.ru'.$order->order->match->teamTwo->logo;
            $row['rightTeam']['name'] = $order->order->match->teamTwo->name;
            $row['winTeam']['logo'] = 'https://24cyber.ru'.$order->order->payTeam->logo;
            $row['winTeam']['name'] = $order->order->payTeam->name;
            $row['buttonsDisplay']['accept'] = !$order->order->match->gameOver&&$order->status==0;
            $row['buttonsDisplay']['cancel'] = false;
            $row['buttonsDisplay']['delete'] = $order->status==0;
            $data['newUserAcceptedBets'][] =  $row;
            unset($row);
        }
        //transactions
        foreach ($user->myTransactionsIn as $key => $tr) {
            $row=[];
            $row['date'] = Yii::$app->formatter->asDate($tr->date, 'php:m.d.Y H:i');
            $row['action'] = 'Депозит на виртуальный счёт';
            $row['status']['text'] = $tr->statusText;
            $row['status']['processed'] = $tr->status == 1 ? true : false;
            $row['sum'] = $tr->amount;
            $row['commission'] = $tr->commision;
            $row['rest'] = $user->balance;
            $data['newUserTransactionsData'][] =  $row;
            unset($row);
        }


        foreach ($user->ordersTransactions as $key => $tr) {
            $row=[];
            $row['date'] = Yii::$app->formatter->asDate($tr->date, 'php:m.d.Y H:i');
            $row['action'] = 'Оплата ордера';
            $row['status']['text'] = $tr->statusText;
            $row['status']['processed'] = $tr->status == 1 ? true : false;
            $row['sum'] = $tr->amount;
            $row['commission'] = $tr->commision;
            $row['rest'] = $user->balance;
            $data['newUserTransactionsData'][] =  $row;
            unset($row);
        }


        foreach ($user->myTransactionsOut as $key => $tr) {
            $row=[];
            $row['date'] = Yii::$app->formatter->asDate($tr->date, 'php:m.d.Y H:i');
            $row['action'] = 'Вывод средств на виртуальный счёт';
            $row['status']['text'] = $tr->statusText;
            $row['status']['processed'] = $tr->status == 1 ? true : false;
            $row['sum'] = $tr->amount;
            $row['commission'] = $tr->commision;
            $row['rest'] = $user->balance;
            $data['newUserTransactionsData'][] =  $row;
            unset($row);
        }
        //orders
        foreach ($user->orders as $key => $order) {
            $row=[];

            $add_status = '';
            if ($order->match->gameOver&&$order->status==0) {
                $add_status = 'Отменен';
            } elseif ($order->status==1&&$order->payersTransactionsSum) {
                $add_status = 'Ожидание матча';
            } elseif ($order->status==2) {
                $add_status = 'Ожидание результата';
            } elseif ($order->status==3) {
                if ($order->payScore>$order->orderScore) {
                    $add_status = 'Проигрыш';
                } else {
                    $add_status = 'Победа';
                }
            }

            $row['bet']['id'] = $order->id;
            $row['bet']['status'] = $order->statusName;

            $row['bet']['ratio'] = $order->rate;
            $row['bet']['sum'] = $order->summ;
            $row['bet']['acceptedSum'] = $order->payersTransactionsSum;
            $row['game']['image'] ='/img/game_'.$order->match->league->game.'.png';
            $row['match']['date']=Yii::$app->formatter->asDate($order->match->date, 'php:m/d/Y H:i');
            $row['match']['noteAboutEndMatch'] = $add_status;


            $row['leftTeam']['logo'] = 'https://24cyber.ru'.$order->match->teamOne->logo;
            $row['leftTeam']['name'] = $order->match->teamOne->name;
            $row['rightTeam']['logo'] = 'https://24cyber.ru'.$order->match->teamTwo->logo;
            $row['rightTeam']['name'] = $order->match->teamTwo->name;
            $row['winTeam']['logo'] = 'https://24cyber.ru'.$order->orderTeam->logo;
            $row['winTeam']['name'] = $order->orderTeam->name;
            $row['buttonsDisplay']['accept'] = $order->status==0&&!$order->match->gameOver;
            $row['buttonsDisplay']['cancel'] = $order->status==1&&!$order->payersTransactionsSum;
            $row['buttonsDisplay']['delete'] = $order->status==0&&!$order->match->gameOver;
            $data['newUserCreatedBets'][] =  $row;
            unset($row);
        }
//        var_dump("<pre>",$posts);die();
        return json_encode($data);
    }



    public function actionDeletePay()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->request->isAjax || !is_numeric(Yii::$app->request->post('pay_id'))) {
            return $this->goHome();
        }
        $id = Yii::$app->request->post('pay_id');
        $pay = Transaction::findOne($id);
        if ($pay->status == 0) {
            $message = 'Заявка №'.$pay->id.' успешно удалена!';
            $pay->goDisable();
            $pay->order->addLog("Транзакция #".$pay->id.", на пользователя #".$pay->agent.", на сумму ".$pay->amount.", в статусе ".$pay->statusText);
        } else {
            $message = 'Вы уже оплатили эту заявку. Удаление невозможно!';
        }

        return $message;
    }


    public function actionPayOrderPay()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->request->isAjax || !is_numeric(Yii::$app->request->post('pay_id'))) {
            return $this->goHome();
        }
        $id = Yii::$app->request->post('pay_id');
        $pay = Transaction::findOne($id);
        $user = User::findOne(Yii::$app->user->id);
        if ($user->balance<$pay->amount) {
            $message = 'У вас недостаточно средств на счету!';
        } else {
            if ($pay->order->match->gameOver==1) {
                $message = 'Матч уже закончился!';
            } else {
                $message = 'Заявка оплачена!';
                $pay->goSuccess();
                $pay->order->addLog("Транзакция #".$pay->id.", на пользователя #".$pay->agent.", на сумму ".$pay->amount.", в статусе ".$pay->statusText);
            }
        }
        // if ($pay->status == 0) {
        //     $message = 'Заявка №'.$pay->id.' успешно удалена!';
        //     $pay->delete();
        // }
        // else $message = 'Вы уже оплатили эту заявку. Удаление невозможно!';

        return $message;
    }

    public function actionPayOrder()
    {
        $id = Yii::$app->request->post('order_id');
        $model = new PayOrder();
        $model->order_id = $id;
        $status = $model->pay();
        if ($status) {
            if ($status==1) {
                return json_encode(['message'=>'Ваш ордер успешно оплачен', 'balance'=>Yii::$app->user->identity->balance]);
            } elseif ($status==3) {
                return json_encode(['message'=>'У Вас не хватает денег на счету.']);
            }
        }
    }


    public function actionOffOrder()
    {
        $id = Yii::$app->request->post('order_id');
        $model =  Orders::findOne($id);
        if ($model->payersTransactionsSum) {
            return 'Ордер уже оплачен';
        } else {
            $model->disable();
            return 'Ваш ордер успешно отменен';
        }
    }


    public function actionDeleteOrder()
    {
        $id = Yii::$app->request->post('order_id');
        $model =  Orders::findOne($id);
        if ($model->status==0) {
            $model->delete();
            return 'Ваш ордер успешно удален!';
        } else {
            return 'ВЫ не можете удалить этот ордер!';
        }
    }


    

    public function actionEditProfile()
    {
        $model = new SettingsUser();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/user/');
        }
        return $this->render('edit', ['model' => $model]);
    }

    public function actionAddAvatar()
    {
        $model = User::findOne(Yii::$app->user->id);
        $image = UploadedFile::getInstanceByName('avatar');
        $directory = Yii::getAlias('@app/web/uploads/images/users/');
        $name = md5($image->baseName.time());
        if ($image->saveAs($directory . $name . '.' . $image->extension)) {
            $model->avatar = '/uploads/images/users/'.$name . '.' . $image->extension;
            $model->save(false);
        }

        return '/uploads/images/users/'.$name . '.' . $image->extension;
    }

    public function actionViewUser($id)
    {
        $user = User::findOne($id);
//        $raiting = $user->userraiting;
//        $avatar = $user->avatar;
        return $this->render('view', ['user' => $user,]);
    }


    /*public function actionUpdateUser()
    {
        if(Yii::$app->user->identity->email)$this->redirect('/');
        $editUser = new UpdateUser();
        if ($editUser->load(Yii::$app->request->post())) {
            if ($editUser->edit()) {
                return $this->goHome();
            }
        }

        return $this->render('/user/update-user', [
            'editUser' => $editUser,
        ]);
    }*/

    public function actions()
    {
        return [
            // 'add-avatar' => [
            //     'class' => 'app\widgets\imgUpload\actions\UploadAction',
            //     'url' => '/uploads/images/users', // Directory URL address, where files are stored.
            //     'path' => '@app/web/uploads/images/users', // Or absolute path to directory where files are stored.
            //     'avatar' => true
            // ],
        ];
    }
}
