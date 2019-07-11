<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `app\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'match_id', 'author_id', 'summ', 'winner', 'status', 'type', 'date', 'up_date'], 'integer'],
            [['rate'], 'number'],
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
    public function search($params, $match_id)
    {
        $query = Orders::find()->where(['match_id'=>$match_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'match_id' => $this->match_id,
            'author_id' => $this->author_id,
            'summ' => $this->summ,
            'winner' => $this->winner,
            'status' => $this->status,
            'type' => $this->type,
            'date' => $this->date,
            'up_date' => $this->up_date,
            'rate' => $this->rate,
        ]);

        return $dataProvider;
    }
}
