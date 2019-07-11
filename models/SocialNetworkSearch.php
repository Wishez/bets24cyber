<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SocialNetwork;

/**
 * SocialNetworkSearch represents the model behind the search form about `app\models\SocialNetwork`.
 */
class SocialNetworkSearch extends SocialNetwork
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_snetwork', 'enable'], 'integer'],
            [['name', 'logo', 'href'], 'safe'],
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
        $query = SocialNetwork::find();

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
            'id_snetwork' => $this->id_snetwork,
            'enable' => $this->enable,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'href', $this->href]);

        return $dataProvider;
    }
}
