<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class PayOrder extends Model
{
    public $order_id;
    public $team_id;
    public $summ;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['order_id', 'required'],
            ['summ', 'integer'],
            ['order_id', 'integer'],
            ['team_id', 'integer'],
            ['summ', 'validateSum'],
        ];
    }

    public function validateSum($attribute, $params)
    {
        $min = 0;
        $max = $this->order->summ*$this->order->rate-$this->order->summ-$this->order->payersTransactionsSum;
        if ($this->$attribute>$max) {
            $this->addError($attribute, 'Cумма не может быть больше, чем : '.$max);
        } elseif ($this->$attribute==$min) {
            $this->addError($attribute, 'Cумма не может быть : '.$min);
        }
    }

    public function attributeLabels()
    {
        return [
            'summ' => 'Сумма',
        ];
    }

    public function repay()
    {
        if ($this->validate()) {
            if (Yii::$app->user->isGuest) {
                $response['message'] = 'Для того что бы поставить ставку - Зарегистируйтесь или войдите в свою учетную запись!';
                $response['error'] = 2;
                return $response;
            }

            if ($this->order->match->gameOver) {
                $response['message'] = 'Матч уже закончился';
                $response['error'] = 1;
                return $response;
            }


            if (strtotime($this->order->match->date) <= time()) {
                $response['message'] = 'Матч уже идет';
                $response['error'] = 1;
                return $response;
            }
            
            if (Yii::$app->user->id!=$this->order->author_id) {
                // $user_trans = $this->order->getPayersTransactions()->andWhere(['agent'=>Yii::$app->user->id])->all();
                if ($this->order->status != Orders::STATUS_OPEN) {
                    $response['message'] = 'Вы можете оплатить только открытый ордер';
                    $response['error'] = 1;
                    return $response;
                }
                if (Yii::$app->user->identity->balance<$this->summ) {
                    $transaction = $this->plain();
                    
                    
                    $response['message'] = 'У Вас недостаточно средств на счету';
                    $response['error'] = 1;
                    return $response;
                } else {
                    $transaction = $this->plain();
                    $transaction->goSuccess();
                    $this->order->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->agent.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
                    $this->order->addLog("Сделана ставка на сумму ".$this->summ." от пользователя ".Yii::$app->user->id);

                    if ((float)$this->order->payersTransactionsSum==($this->order->summ*$this->order->rate-$this->order->summ)) {
                        $order = Orders::findOne($this->order->id);
                        $order->status  =2;
                        
                        $order->save(false);
                        $log = $this->order->addLog("Смена статуса ордера на статус - ".$this->order->statusName.".");
                    }
                    
                    
                    $response['message'] = 'Ваша заявка принята!';
                    $response['error'] = 0;
                    $response['balance'] = Yii::$app->user->identity->balance;
                    return $response;
                }
            } else {
                $response['message'] = 'Вы не можете поставить ставку на свой ордер!';
                $response['error'] = 1;
                return $response;
            }
        }
        return false;
    }

    public function pay()
    {
        if ($this->validate()) {
            $match = Match::findOne($this->order->match_id);
            if (!$match->gameOver&&strtotime($this->order->match->date) >= time()) {
                if ($this->order->status == Orders::STATUS_OPEN) {
                    return 4;
                }
                $this->summ = $this->order->summ;
                $this->team_id = $this->order->winner;
                if (Yii::$app->user->identity->balance<$this->summ) {
                    //$this->plain($this->order->authorTransactions->id);
                    return 3;
                } else {
                    $transaction = $this->order->authorTransactions;
                    $transaction->goSuccess();
                    $this->order->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->agent.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
                    $order = Orders::findOne($this->order->id);
                    $order->status  = 1;
                        
                    $order->save(false);
                    // var_dump( $this->order->status );
                    // exit();
                    $this->order->addLog("Оплата ордера на сумму ".$this->summ.".");
                    $this->order->addLog("Смена статуса ордера на статус - ".$this->order->statusName.".");
                    return 1;
                }
            }
            return false;
        }
        return false;
    }

    public function plain($id=false)
    {
        if ($id) {
            $transaction = Transaction::find($id);
        }
        
        if (!isset($transaction)||!$transaction) {
            $transaction = new Transaction();
        }

        $transaction->type = 4;
        // var_dump($transaction);
        // exit();
        $transaction->amount = ($this->summ ? $this->summ : $this->order->summ);
        $transaction->agent = Yii::$app->user->id;
        $transaction->partner = $this->order->id;
        $transaction->team = ($this->team_id ? $this->team_id : $this->order->winner);
        if ($transaction->run()) {
            $this->order->updateTime();
            $this->order->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->agent.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
            return $transaction;
        }
        return false;
    }


    public function getOrder()
    {
        // if ($id) {
        //     $this->order_id = $id;
        // }
        if (!$this->order_id) {
            return false;
        }
        return Orders::findOne($this->order_id);
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
}
