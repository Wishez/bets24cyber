<?php

namespace app\modules\forum\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\forum\models\ForumTopic;

/**
 * ForumTopicSearch represents the model behind the search form about `app\modules\forum\models\ForumTopic`.
 */
class ForumTopicSearch extends ForumTopic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_ftopic', 'id_fcategory', 'id_user_owner', 'created_at', 'updated_at', 'views', 'enable', 'visible'], 'integer'],
            [['name'], 'safe'],
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
        $query = ForumTopic::find();

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
            'id_ftopic' => $this->id_ftopic,
            'id_fcategory' => $this->id_fcategory,
            'id_user_owner' => $this->id_user_owner,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'views' => $this->views,
            'enable' => $this->enable,
            'visible' => $this->visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
