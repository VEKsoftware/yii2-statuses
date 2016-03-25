<?php

namespace statuses\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StatusesSearch represents the model behind the search form about `statuses\models\Statuses`.
 */
class StatusesSearch extends Statuses
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'doc_type'], 'integer'],
            [['name', 'description', 'symbolic_id'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
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
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'doc_type' => $this->doc_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'symbolic_id', $this->symbolic_id]);

        return $dataProvider;
    }

    /**
     * @param $model
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchUnlink($model, $params)
    {
        $query = Statuses::find()
            ->where([
                'and',
                ['not', ['id' => $model->id]],
                ['doc_type' => $model->doc_type],
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'symbolic_id', $this->symbolic_id]);

        return $dataProvider;
    }
}
