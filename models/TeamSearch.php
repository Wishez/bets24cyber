<?php
namespace app\models;

use app\models\Team;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TeamSearch extends Team{


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Team::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['country_code' => $this->country_code])
            ->andFilterWhere(['like', 'team_name', $this->team_name])
            /*->orderBy("team_name ASC")*/;

        return $dataProvider;
    }

}
