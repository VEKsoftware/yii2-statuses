<?php

namespace statuses\models;

use statuses\components\CommonRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "statuses_doctypes".
 *
 * @property int $id
 * @property string $name
 * @property string $symbolic_id
 * @property Statuses[] $statuses
 */
class StatusesDoctypes extends CommonRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statuses_doctypes}}';
    }

    /**
     * @return static[] List of doc types
     */
    public static function listDocs()
    {
        return static::findDocs()->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findDocs()
    {
        return static::find();
    }

    /**
     * @param $doc_string
     * @return static
     */
    public static function findDoc($doc_string)
    {
        return static::find()->where(['symbolic_id' => $doc_string])->one();
    }

    /**
     * Create list [ $this->id => $this->name ]
     *
     * @return array
     */
    public static function createDropdown()
    {
        $models = self::find()->all();
        if (!empty($models)) {
            return ArrayHelper::map($models, 'id', 'name');
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'symbolic_id'], 'required'],
            [['name', 'symbolic_id'], 'string', 'max' => 200],
            [['symbolic_id'], 'unique'],
            ['symbolic_id', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.]+$/'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('statuses', 'ID'),
            'name' => Yii::t('statuses', 'Statuses Doctypes Name'),
            'symbolic_id' => Yii::t('statuses', 'Statuses Doctypes Symbolic ID'),
        ];
    }

    /**
     * @inherit
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \statuses\Statuses::getInstance()->accessClass,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(\statuses\models\Statuses::className(), ['doc_type' => 'id'])->indexBy('symbolic_id');
    }
}
