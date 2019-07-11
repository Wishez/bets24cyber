<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public $virt;
    public $fund;
    public function rules()
    {
        return [
            [['id', 'amount','type','agent','partner', 'commision', 'note', 'date', 'status','virt','fund'], 'safe'],
            [['username', 'avatar', 'birthday', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'role', 'eauth'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id)
    {
        $query = Transaction::find();
        if ($id) {
            $query->where(['or', ['and', ['type'=>3, 'agent'=>$id ]],['and', ['type'=>4, 'partner'=>$id]]]);

            
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->fund) {
            $query->andFilterWhere(['or',
                ['type'=>[Transaction::TYPE_RETURN,Transaction::TYPE_FUND],'agent'=>$this->fund],
                ['type'=>Transaction::TYPE_REFIll,'partner'=>$this->fund]
                
            ]);
        }

        if ($this->virt) {
            $query->andFilterWhere(['or',
                ['type'=>[Transaction::TYPE_REFIll,Transaction::TYPE_ORDER],'agent'=>$this->virt],
                ['type'=>[Transaction::TYPE_RETURN,Transaction::TYPE_PAY],'partner'=>$this->virt]
                
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'commision' => $this->commision,
            'date' => $this->date
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        $query->orderBy('date DESC');

        return $dataProvider;
    }
}
