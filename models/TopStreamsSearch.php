<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TopStreams;

/**
 * TopStreamsSearch represents the model behind the search form about `app\models\TopStreams`.
 */
class TopStreamsSearch extends TopStreams
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tsream', 'views', 'likes', 'visible'], 'integer'],
            [['title', 'date', 'link', 'img'], 'safe'],
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
        $query = TopStreams::find();

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
            'id_tsream' => $this->id_tsream,
            'date' => $this->date,
            'views' => $this->views,
            'likes' => $this->likes,
            'visible' => $this->visible,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'img', $this->img]);

        return $dataProvider;
    }
}
