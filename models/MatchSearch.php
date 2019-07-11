<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Match;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * MatchSearch represents the model behind the search form about `app\models\Match`.
 */
class MatchSearch extends Match
{

    public $is_succes;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'team1', 'team2', 'date', 'score_left', 'score_right','over'], 'safe'],
            [['is_succes'], 'integer']
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
    public function search($params)
    {
      
        $query = Match::find()->innerJoinWith('orders');





        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        

        $query->andFilterWhere([
            'id' => $this->id,
            'team1' => $this->team1,
            'team2' => $this->team2,
            'date' => $this->date,
            'score_left' => $this->score_left,
            //'over' => $this->over,
        ]);
        if (isset($this->is_succes)) {
          $orders = Orders::find()->innerJoinWith('pays')->where(['transactions.status'=>0])->all();
          if ($this->is_succes==0) {
            $query->andFilterWhere([
            'matches.id' => ArrayHelper::map($orders, 'match_id', 'match_id') ? ArrayHelper::map($orders, 'match_id', 'match_id') : 0,
            ]);
          }
          else
          {
            $query->andFilterWhere([
            'not in',
            'matches.id',
            ArrayHelper::map($orders, 'match_id', 'match_id') ? ArrayHelper::map($orders, 'match_id', 'match_id') : 0
            ]);
          }
        }

        $query->orderBy(['date'=>SORT_DESC]);


        return $dataProvider;
    }
}
