<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;

/**
 * NewsSearch represents the model behind the search form about `app\models\News`.
 */
class NewsSearch extends News
{
    public $username;
    public $categoryname;
    public $bookmakername;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_news', 'id_user', 'id_category', 'id_bmaker', 'show_in_footer', 'created_at', 'updated_at', 'sort'], 'integer'],
            [['title', 'desc', 'text', 'img','username','categoryname'], 'safe'],
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
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id_news',
                'title',
                'desc',
                'text',
                'img',
                'id_user'=>[
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'id_category'=>[
                    'asc' => ['category.name' => SORT_ASC],
                    'desc' => ['category.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'show_in_footer',
                'created_at',
                'updated_at',
                'sort'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['user']);
        $query->joinWith(['category']);

        $query->andFilterWhere([
            'id_news' => $this->id_news,
            'id_user' => $this->id_user,
            'id_category' => $this->id_category,
            'show_in_footer' => $this->show_in_footer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'user.username', $this->id_user])
            ->andFilterWhere(['like', 'category.name', $this->id_category])
            ->andFilterWhere(['like', 'img', $this->img]);

        return $dataProvider;
    }
}
