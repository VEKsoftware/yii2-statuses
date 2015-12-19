<?php

namespace statuses\models;

use Yii;

use partneruser\models\RefRights;

/**
 * This is the model class for table "statuses_links".
 *
 * @property integer $status_from
 * @property integer $status_to
 * @property integer $right_id
 *
 * @property RefRights $right
 * @property Statuses $statusFrom
 * @property Statuses $statusTo
 */
class StatusesLinks extends \statuses\components\CommonRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statuses_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_from', 'status_to', 'right_id'], 'required'],
            [['status_from', 'status_to', 'right_id'], 'integer'],
            [['right'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_from' => Yii::t('statuses', 'Statuses Links Status From'),
            'status_to' => Yii::t('statuses', 'Statuses Links Status To'),
            'right_id' => Yii::t('statuses', 'Statuses Links Right ID'),
            
            'right' => Yii::t('statuses', 'Statuses Links Right'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRight()
    {
        return $this->hasOne(RefRights::className(), ['id' => 'right_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusFrom()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusTo()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status_to']);
    }
}
