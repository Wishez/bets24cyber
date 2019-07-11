<?php

namespace app\modules\forum\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\forum\models\ForumCategory;

/**
 * ForumCategorySearch represents the model behind the search form about `app\modules\forum\models\ForumCategory`.
 */
class ForumCategorySearch extends ForumCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_fcategory', 'id_owner_category'], 'integer'],
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
        $query = ForumCategory::find();

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
            'id_fcategory' => $this->id_fcategory,
            'id_owner_category' => $this->id_owner_category,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
