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
    public $statusName;
    public $rightName;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_from', 'status_to', 'right_id'], 'integer'],
            [['statusName', 'rightName'], 'string'],
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
            ->with('statusFrom')
            ->where(['status_from' => $statusesId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        // надо здесь создать условия для фильтрации элементов единиц StatusLink
        // по именам связанных статусов и прав
        
        $query
        ->joinWith(['statusTo' => function($query) {
            $query->where([
                'or',
                '"statuses"."name" LIKE '."'%".$this->statusName."%'",
                '"statuses"."symbolic_id" LIKE '."'%".$this->statusName."%'"
            ]);
        } ])
        ->joinWith(['right' => function($query) {
            $query->where('"ref_rights"."name" LIKE '."'%".$this->rightName."%'");
        } ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'statusName' => [
                    'asc' => [
                        'statuses.name' => SORT_ASC
                    ],
                    'desc' => [
                        'statuses.name' => SORT_DESC
                    ],
                    'default' => SORT_ASC,
                ],
                'rightName' => [
                    'asc' => [
                        'ref_rights.name' => SORT_ASC
                    ],
                    'desc' => [
                        'ref_rights.name' => SORT_DESC
                    ],
                    'default' => SORT_ASC,
                ],
            ]
        ]);
        
        return $dataProvider;
    }
}
