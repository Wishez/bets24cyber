<?php

namespace app\modules\forum\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\forum\models\ForumPost;

/**
 * ForumPostSearch represents the model behind the search form about `app\modules\forum\models\ForumPost`.
 */
class ForumPostSearch extends ForumPost
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_fpost', 'id_ftopic', 'id_user', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'safe'],
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
        $query = ForumPost::find();

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
            'id_fpost' => $this->id_fpost,
            'id_ftopic' => $this->id_ftopic,
            'id_user' => $this->id_user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
