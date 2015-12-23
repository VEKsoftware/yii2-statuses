<?php

namespace statuses\models;

use Yii;

/**
 * This is the model class for table "statuses_doctypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $symbolic_id
 *
 * @property Statuses[] $statuses
 */
class StatusesDoctypes extends \statuses\components\CommonRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%statuses_doctypes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'symbolic_id'], 'string'],
            [['symbolic_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('statuses', 'ID'),
            'name' => Yii::t('statuses', 'Name'),
            'symbolic_id' => Yii::t('statuses', 'Symbolic ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(Statuses::className(), ['doc_type' => 'id']);
    }
    
    /**
     * create list [ $this->id => $this->name ]
     */
    public function createDropdown() {
        
        self::find()->all();
        
    }
}
