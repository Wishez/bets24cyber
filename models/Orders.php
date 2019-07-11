<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $match_id
 * @property integer $author_id
 * @property integer $summ
 * @property integer $winner
 * @property integer $status
 * @property integer $type
 * @property integer $date
 * @property double $rate
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    const STATUS_NEW = 0;
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 2;
    const STATUS_OVER = 3;

    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'author_id', 'summ', 'winner', 'status', 'date', 'rate'], 'required'],
            [['match_id', 'author_id', 'summ', 'winner', 'status', 'type', 'date','up_date'], 'integer'],
            [['rate'], 'number', 'min'=>1.01, 'max'=>100],
            // ['match_id', 'checkMatch'],
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->up_date = time();
     
            return true;
        }
        return false;
    }

    public function add()
    {
        $this->date = time();
        $this->status = self::STATUS_NEW;
        if ($this->save()) {
            $this->addLog("Создан ордер!");
            $model = new PayOrder();
            $model->order_id = $this->id;
            $status = $model->plain();
            return true;
        }
        return false;
    }

    public function updateTime()
    {
        $this->up_date = time();
        $this->save(false);
    }

    public function processing()
    {
        if (($this->orderScore>$this->payScore)||($this->orderScore==$this->payScore&&$this->winner==0)) {
            $summ = $this->summ+$this->payersTransactionsSum;
            $transaction = new Transaction();
            $transaction->type = Transaction::TYPE_PAY;
            $transaction->amount = $summ;
            $transaction->agent = $this->id;
            $transaction->partner = $this->author_id;
            $transaction->run();
            $this->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->partner.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
            $this->addLog("Ордер выигран на сумму ".$transaction->amount.".");
        } else {
            $max_pay = $this->summ*($this->rate-1);
            $max_perc = $this->payersTransactionsSum/$max_pay;
            foreach ($this->getPayersTransactions()->andWhere(['status'=>1])->all() as $key => $pay) {
                $perc = $pay->amount/$max_pay;
                $transaction = new Transaction();
                $transaction->type = Transaction::TYPE_PAY;
                $transaction->amount = $this->summ*$perc+$pay->amount;
                $transaction->partner = $pay->agent;
                $transaction->agent = $this->id;
                $transaction->run();
                $this->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->partner.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
                $this->addLog("Ставка пользователя ".$transaction->partner." выиграна на сумму ".$transaction->amount.".");
            }
            $this->addLog("Проигрыш ордера на сумму ".($this->summ*$max_perc).".");
            if ($max_perc!=1) {
                $rest_summ = $this->summ*(1-$max_perc);
                $transaction = new Transaction();
                $transaction->type = Transaction::TYPE_PAY;
                $transaction->amount = $rest_summ;
                $transaction->agent = $this->id;
                $transaction->partner = $this->author_id;
                $transaction->run();
                $this->addLog("Транзакция #".$transaction->id.", на пользователя #".$transaction->partner.", на сумму ".$transaction->amount.", в статусе ".$transaction->statusText);
                $this->addLog("Возврат суммы ".$rest_summ.".");
            }
        }
        $this->status = self::STATUS_OVER;
        $this->addLog("Ордер переходит в статус ".$this->statusName.".");
        $this->save(false);
    }

    public function disable()
    {
        $this->up_date = time();
        $this->status = self::STATUS_NEW;
        $this->addLog("Ордер переходит в статус ".$this->statusName.".");
        if ($this->save()) {
            if ($this->authorTransactions) {
                $this->authorTransactions->goDisable();
            }

            if (!$this->match->gameOver&&strtotime($order->match->date) > time()) {
                $model = new PayOrder();
                $model->order_id = $this->id;
                $status = $model->plain();
            }
            return true;
        }
        return false;
    }



    // public function checkMatch()
    // {
    //     $match = Match::findOne($this->match_id);

    //     if ($match->gameOver) {
    //         $this->addError('password', 'Матч завершился. Попробуйте поставить ставку на другой матч');
    //     }
    // }

    /**
     * @inheritdoc
     */
    
    public function getStatuses()
    {
        return
        [
            0=>'Новый',
            1=>'Открытый',
            2=>'Закрытый',
            3=>'Завершен',
        ];
    }
    
    public function getStatusName()
    {
        return $this->statuses[$this->status];
    }
    public function getTeam()
    {
        $teams = [1=>'teamFirst', 2=>'teamSecond'];
        return $teams[$this->winner];
    }

    public function getPayTeam()
    {
        $teams = [1=>'teamFirst', 2=>'teamSecond'];
        $team = $teams[($this->winner==1 ? 2 : 1)];
        return $this->match->$team;
    }

    public function getPayScore()
    {
        $scores = [1=>'score_left', 2=>'score_right'];
        $score = $scores[($this->winner==1 ? 2 : 1)];
        return $this->match->$score;
    }



    public function getOrderTeam()
    {
        $teams = [1=>'teamFirst', 2=>'teamSecond'];
        $team = $teams[$this->winner];
        return $this->match->$team;
    }


    public function getOrderScore()
    {
        $scores = [1=>'score_left', 2=>'score_right'];
        $score = $scores[$this->winner];
        return $this->match->$score;
    }

    public function getAuthorTransactions()
    {
        return $this->hasOne(Transaction::className(), ['agent' => 'author_id', 'partner'=>'id'])->where(['!=', 'status', Transaction::STATUS_DISABLED])->andWhere(['transactions.type'=>4]);
    }

    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }
    public function getPayersTransactions()
    {
        return $this->hasMany(Transaction::className(), ['partner' => 'id'])->where(['!=', 'agent', $this->author_id])->andWhere(['!=', 'status', Transaction::STATUS_DISABLED])->andWhere(['transactions.type'=>4]);
    }


    public function getPays()
    {
        return $this->hasMany(Transaction::className(), ['agent' => 'id'])->where(['transactions.type'=>3])->andWhere(['!=','transactions.status', '2']);
    }

    public function getLogs()
    {
        return $this->hasMany(OrderLogs::className(), ['order_id' => 'id']);
    }

    public function addLog($text)
    {
        $log = new OrderLogs();
        $log->order_id = $this->id;
        $log->text = $text;
        $log->date = time();
        $log->save(false);
    }

    public function getPayersTransactionsSum()
    {
        return (int)$this->getPayersTransactions()->andWhere(['status'=>1])->sum('amount');
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Матч',
            'author_id' => 'Юзер',
            'summ' => 'Ставка',
            'winner' => 'Комманда',
            'status' => 'Статус',
            'type' => 'Тип',
            'date' => 'Дата Создания',
            'up_date' => 'Дата Обновления',
            'rate' => 'Коэфициент',
            'statusName'=>'Статус',
        ];
    }
}
