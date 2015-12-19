<?php

namespace statuses\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use statuses\models\Statuses;

/**
 * StatusesSearch represents the model behind the search form about `statuses\models\Statuses`.
 */
class StatusesSearch extends Statuses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doc_type'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = Statuses::find();

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
            'doc_type' => $this->doc_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
