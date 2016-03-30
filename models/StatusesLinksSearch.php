<?php

namespace statuses\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * StatusesLinksSearch represents the model behind the search form about `statuses\models\Statuses`.
 */
class StatusesLinksSearch extends StatusesLinks
{
    public $statusName;
    public $rightName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_from', 'status_to'], 'integer'],
            [['statusName', 'rightName', 'right_tag'], 'string'],
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
     * @param $statusesId
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($statusesId, $params)
    {
        $query = StatusesLinks::find()
            ->with('statusFrom')
            ->where(['status_from' => $statusesId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /* TODO  здесь нужно создать условия для фильтрации элементов единиц StatusLink по именам связанных статусов и прав */
        /** @var ActiveQuery $query */
        $query
            ->joinWith(['statusTo' => function ($query) {
                /** @var ActiveQuery $query */
                $query->where([
                    'or',
                    '"statuses"."name" LIKE ' . "'%" . $this->statusName . "%'",
                    '"statuses"."symbolic_id" LIKE ' . "'%" . $this->statusName . "%'",
                ]);
            }]);
//            ->joinWith(['right' => function ($query) {
//                /** @var ActiveQuery $query */
//                $query->where('"ref_rights"."name" LIKE ' . "'%" . $this->rightName . "%'");
//            }]);

        $dataProvider->setSort([
            'attributes' => [
                'statusName' => [
                    'asc' => [
                        'statuses.name' => SORT_ASC,
                    ],
                    'desc' => [
                        'statuses.name' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ],
                'right_tag' => [
                    'asc' => [
                        'right_tag' => SORT_ASC,
                    ],
                    'desc' => [
                        'right_tag' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ],
            ],
        ]);

        return $dataProvider;
    }
}
