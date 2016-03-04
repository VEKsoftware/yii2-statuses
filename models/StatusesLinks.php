<?php

namespace statuses\models;

use statuses\components\CommonRecord;
use statuses\Statuses;
use Yii;

/**
 * This is the model class for table "statuses_links".
 *
 * @property int $status_from
 * @property int $status_to
 * @property int $right_id
 * @property mixed $right
 * @property Statuses $statusFrom
 * @property Statuses $statusTo
 */
class StatusesLinks extends CommonRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statuses_links}}';
    }

    /**
     * list of primary keys for model identification.
     */
    public static function primaryKey()
    {
        return ['status_from', 'status_to', 'right_id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_from', 'status_to', 'right_id'], 'required'],
            [['status_from', 'status_to', 'right_id'], 'integer'],

            [['status_from', 'status_to', 'right_id'], 'validateExists'],
        ];
    }

    /**
     * check DB for unique link (exists or not).
     */
    public function validateExists()
    {
        $link = self::find()
            ->where([
                'status_from' => $this->status_from,
                'status_to' => $this->status_to,
                'right_id' => $this->right_id,
            ])
            ->one();

        if (!is_null($link)) {
            $this->addError('status_from', Yii::t('statuses', 'Statuses Links is exists.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_from' => Yii::t('statuses', 'Statuses Links Status From'),
            'status_to' => Yii::t('statuses', 'Statuses Links Status To'),
            'right_id' => Yii::t('statuses', 'Statuses Links Right ID'),

            'statusName' => Yii::t('statuses', 'Statuses Links Status To'),
            'rightName' => Yii::t('statuses', 'Statuses Links Right ID'),

            'right' => Yii::t('statuses', 'Statuses Links Right'),
        ];
    }

    /**
     * @inherit
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => Statuses::getInstance()->accessClass,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRight()
    {
        /** @var Statuses $class */
        $class = Statuses::getInstance()->accessRightsClass;
        return $this->hasOne($class::className(), ['id' => 'right_id']);
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

    /**
     * {@inheritdoc}
     */
    public function getStatusName()
    {
        return $this->statusTo->symbolic_id . ' - ' . $this->statusTo->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRightName()
    {
        return $this->right->name;
    }
}
