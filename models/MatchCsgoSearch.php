<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Match;

/**
 * MatchCsgoSearch represents the model behind the search form about `app\models\Match`.
 */
class MatchCsgoSearch extends Match
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_match', 'id_match_steam', 'radiant_team_id', 'dire_team_id', 'id_league', 'match_sort_number', 'type_of_game'], 'integer'],
            [['start_time', 'link_video','chats', 'team1', 'team2'], 'safe'],
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
        $query = Match::find();

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
            'id_match' => $this->id_match,
            'id_match_steam' => $this->id_match_steam,
            'radiant_team_id' => $this->radiant_team_id,
            'dire_team_id' => $this->dire_team_id,
            'id_league' => $this->id_league,
            'match_sort_number' => $this->match_sort_number,
            'type_of_game' => $this->type_of_game,
        ]);

        $query->andFilterWhere(['like', 'start_time', $this->start_time])
            ->andFilterWhere(['like', 'link_video', $this->link_video])
            ->andFilterWhere(['like', 'chats', $this->chats])
            ->andFilterWhere(['like', 'team1', $this->team1])
            ->andFilterWhere(['like', 'team2', $this->team2]);

        return $dataProvider;
    }
}
