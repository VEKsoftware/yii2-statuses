<?php

namespace statuses\models;

use Yii;

use yii\helpers\ArrayHelper;

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
            [['name', 'symbolic_id'], 'required'],
            [['name', 'symbolic_id'], 'string', 'max' => 200 ],
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
            'access'=>[
                'class'=>\statuses\Statuses::getInstance()->accessClass,
//                'relation'=>[$this,'getUserRelationName'],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findDocs()
    {
        return static::find();
    }

    /**
     * @return static[] List of doc types
     */
    public static function listDocs()
    {
        return static::findDocs()->all();
    }

    /**
     * @return static
     */
    public static function findDoc($doc_string)
    {
        return static::find()->where(['symbolic_id' => $doc_string])->one();
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
    public static function createDropdown() {
        
        $models = self::find()->all();
        if( !empty( $models ) ) return ArrayHelper::map( $models, 'id', 'name' );
        return [];
        
    }
}
