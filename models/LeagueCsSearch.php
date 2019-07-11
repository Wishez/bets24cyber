<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\League;

/**
 * LeagueCsSearch represents the model behind the search form about `app\models\League`.
 */
class LeagueCsSearch extends League
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_league', 'id_league_steam', 'prize', 'type_of_game'], 'integer'],
            [['name', 'link','stream','chats'], 'safe'],
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
        $query = League::find();

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
            'id_league' => $this->id_league,
            'id_league_steam' => $this->id_league_steam,
            'prize' => $this->prize,
            'type_of_game' => $this->type_of_game,
            'type_of_game'=>'2',
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'chats', $this->chats])
            ->andFilterWhere(['like', 'stream', $this->stream]);

        return $dataProvider;
    }
}
