<?php

namespace statuses\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use statuses\models\Statuses;

/**
 * StatusesLinksSearch represents the model behind the search form about `statuses\models\Statuses`.
 */
class StatusesLinksSearch extends StatusesLinks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_from', 'status_to', 'right_id'], 'integer'],
            [['right'], 'safe']
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
    public function search($statusesId, $params)
    {
        $query = StatusesLinks::find()
            ->where(['status_from' => $statusesId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
        
        /*
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
        */
    }
}
