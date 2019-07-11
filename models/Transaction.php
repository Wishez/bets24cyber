<?php
namespace app\models;

//use phpbb\log\null;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property mixed userraiting
 * @property mixed avatar
 * @property mixed birthday
 * @property string role
 */
class Transaction extends ActiveRecord
{


//    public $eauth;

//    public $birthday;
//    public $avatar;

//    public $userraiting;

    /**
     * @inheritdoc
     */
    const STATUS_PROCCES = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_DISABLED = 2;
    const STATUS_LOOSE = 3;

    const TYPE_REFIll = 0;
    const TYPE_RETURN = 1;
    const TYPE_FUND = 2;
    const TYPE_PAY = 3;
    const TYPE_ORDER = 4;



    public static function tableName()
    {
        return '{{transactions}}';
    }
    public function rules()
    {
        return [[['amount', 'commision', 'agent', 'partner', 'team', 'data_commision', 'type', 'status', 'note', 'data_commision'], 'safe']];
    }
    public function getTypeText()
    {
        $names = ['пополнение', 'Возврат клиенту', 'Перевод из фонда в фонд', 'Выплата', 'Оплата ордера'];
        return $names[$this->type];
    }
    public function getStatusText()
    {
        $names = ['В процессе', 'Выполнено', 'Отменено', 'Неудача'];
        return $names[$this->status];
    }
    public function getStatuses()
    {
        $names = ['В процессе', 'Выполнено', 'Отменено', 'Неудача'];
        return $names;
    }
    public function getStatusesPay()
    {
        $names = ['Не оплачен', 'Оплачен', 'Отменено', 'Неудача'];
        return $names;
    }
    public function getPayText()
    {
        $names = ['Не оплачен', 'Оплачен', 'Отменено', 'Неудача'];
        return $names[$this->status];
    }
    public function getBody()
    {
        if ($this->type == self::TYPE_REFIll) {
            return 'С виртуального счета "'.User::findOne($this->agent)->email.' (#'.$this->agent.')" произведено пополнение фонда "'.$this->partner.'"';
        } elseif ($this->type == self::TYPE_RETURN) {
            return 'С фонда "'.$this->agent.'" произведен возврат на виртуальный счет "'.User::findOne($this->partner)->email.' (#'.$this->partner.')"';
        } elseif ($this->type == self::TYPE_FUND) {
            return 'С фонда "'.$this->agent.'" произведено пополнение фонда "'.$this->partner.'"';
        } elseif ($this->type == self::TYPE_PAY) {
            return 'Выплата на виртуальный счет "'.User::findOne($this->partner)->email.' (#'.$this->partner.')" с комммисией "'.$this->data_commision.'"';
        } elseif ($this->type == self::TYPE_ORDER) {
            return 'С виртуального счета "'.User::findOne($this->agent)->email.' (#'.$this->agent.')" произведена оплата ордера "'.$this->partner.'" на команду "'.$this->team.'"';
        }
    }
    private function parseCommision()
    {
        if (preg_match('/%/', $this->commision)) {
            $x = 1;
            $this->commision = preg_replace('/%/', '', $this->commision);
            if ($this->commision < 0) {
                $x = -1;
                $this->commision = -1 * (int)$this->commision;
            }
            $this->commision = $this->amount / 100 * $this->commision * $x;
        }
        if (empty($this->commision)) {
            $this->commision = 0;
        }
    }
    public static function getId($text)
    {
        if (preg_match('/\(#(\d+?)\)/', $text, $matches)) {
            return $matches[1];
        }
        return $text;
    }

    
    public function run()
    {
        $this->date = date('Y-m-d H:i:s');
        $this->parseCommision();
        $this->status = self::STATUS_PROCCES;
        $this->agent = self::getId($this->agent);
        $this->partner = self::getId($this->partner);

        $this->save();

        // if($this->type == self::TYPE_REFIll){
        //     $user = User::findOne($this->agent);
        //     $fund = Funds::findOne($this->partner);

        //     $user->updateCounters(['balance' => (-1 * $this->amount)]);
        //     $fund->updateCounters(['balance' => $this->amount - $this->commision]);
        //     $this->save();

        // }else if($this->type == self::TYPE_RETURN){
        //     $user = User::findOne($this->partner);
        //     $fund = Funds::findOne($this->agent);

        //     $user->updateCounters(['balance' => $this->amount - $this->commision]);
        //     $fund->updateCounters(['balance' => (-1 * $this->amount)]);
        //     $this->save();
        // }else if($this->type == self::TYPE_FUND){
        //     $fund2 = Funds::findOne($this->partner);
        //     $fund1 = Funds::findOne($this->agent);

        //     $fund1->updateCounters(['balance' => (-1 * $this->amount)]);
        //     $fund2->updateCounters(['balance' => $this->amount - $this->commision]);
        //     $this->save();
        // }else if($this->type == self::TYPE_PAY){
        //     $user = User::findOne($this->partner);
        //     $this->data_commision = $user->commision;

        //     if(empty($this->data_commision)){
        //         $this->data_commision = Settings::getValues()['MAIN_SITE_COMMISSION'];
        //     }
        //     $user->updateCounters(['balance' => $this->amount - $this->commision]);
        //     $this->save();
        // }else if($this->type == self::TYPE_ORDER){
        //     $this->save();
        // }
        return true;
    }
    public function goDisable()
    {
        if ($this->status==STATUS_SUCCESS) {
            if ($this->type == self::TYPE_ORDER) {
                $user = User::findOne($this->agent);
                $user->updateCounters(['balance' => (1 * $this->amount)]);
            } elseif ($this->type == self::TYPE_PAY) {
                $user = User::findOne($this->partner);
                if ($user->commision) {
                    $this->commision = $user->commision;
                }
                $user->updateCounters(['balance' => (-1 * ($this->amount+$this->commision))]);
            }
        }
        $this->status = self::STATUS_DISABLED;
        
        $this->save();
    }
    public function goSuccess()
    {
        $this->status = self::STATUS_SUCCESS;
        if ($this->type == self::TYPE_REFIll) {
            $user = User::findOne($this->agent);
            $fund = Funds::findOne($this->partner);

            $user->updateCounters(['balance' => $this->amount]);
            $fund->updateCounters(['balance' => $this->amount + $this->commision]);
        } elseif ($this->type == self::TYPE_RETURN) {
            $user = User::findOne($this->partner);
            $fund = Funds::findOne($this->agent);
            if ($user->commision) {
                $this->commision = $user->commision;
            }

            $user->updateCounters(['balance' => $this->amount + $this->commision]);
            $fund->updateCounters(['balance' => -1 * ($this->amount - $this->commision)]);
        } elseif ($this->type == self::TYPE_FUND) {
            $fund2 = Funds::findOne($this->partner);
            $fund1 = Funds::findOne($this->agent);

            $fund1->updateCounters(['balance' => (-1 * $this->amount)]);
            $fund2->updateCounters(['balance' => $this->amount + $this->commision]);
        } elseif ($this->type == self::TYPE_PAY) {
            $user = User::findOne($this->partner);
            $this->data_commision = $user->commision;

            if (empty($this->data_commision)) {
                $this->data_commision = Settings::getValues()['MAIN_SITE_COMMISSION'];
            }
            $user->updateCounters(['balance' => $this->amount - $this->data_commision]);
        } elseif ($this->type == self::TYPE_ORDER) {
            $user = User::findOne($this->agent);
            $user->updateCounters(['balance' => (-1 * $this->amount)]);
        }
        $this->save();
    }
    public static function getUserName($id)
    {
        $user = User::find()->where(['id' => $id])->asArray()->one();
        return $user['username'].' ('.$user['email'].') Баланс: '.$user['balance'].' (#'.$user['id'].')';
    }
    public static function getFundName($id)
    {
        $fund = Funds::find()->where(['id' => $id])->asArray()->one();
        return 'Фонд "'.$fund['name'].'" (#'.$fund['id'].')';
    }
    public function newAgents()
    {
        if ($this->type == self::TYPE_REFIll) {
            $this->agent = self::getUserName($this->agent);
            $this->partner = self::getFundName($this->partner);
        } elseif ($this->type == self::TYPE_RETURN) {
            $this->agent = self::getFundName($this->agent);
            $this->partner = self::getUserName($this->partner);
        } elseif ($this->type == self::TYPE_FUND) {
            $this->agent = self::getFundName($this->agent);
            $this->partner = self::getFundName($this->partner);
        } elseif ($this->type == self::TYPE_PAY) {
            $this->partner = self::getUserName($this->partner);
        } elseif ($this->type == self::TYPE_ORDER) {
            $this->agent = self::getUserName($this->agent);
        }
    }
    public function edit()
    {
        $this->agent = self::getId($this->agent);
        $this->partner = self::getId($this->partner);
        $this->parseCommision();

        return $this->save();
    }


    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'partner']);
    }

    public function getWinSumm()
    {
        $rate = $this->amount/($this->order->summ*$this->order->rate-$this->order->summ);
        return $this->order->summ*$rate+$this->amount;
    }
}
