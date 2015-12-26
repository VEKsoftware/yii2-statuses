<?php

namespace statuses\models;

use Yii;

use statuses\models\StatusesLinks;
use statuses\models\StatusesDoctypes;

/**
 * This is the model class for table "statuses".
 *
 * @property integer $id
 * @property integer $doc_type
 * @property string $name
 * @property string $description
 *
 * @property StatusesLink[] $statusesLinks
 * @property StatusesLink[] $statusesLinks0
 */
class Statuses extends \statuses\components\CommonRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%statuses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_type','name','symbolic_id'], 'required'],
            [['doc_type'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['symbolic_id'], 'string'],
            [['doc_type','symbolic_id'], 'unique', 'targetAttribute' => ['doc_type','symbolic_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('statuses', 'ID'),
            'doc_type' => Yii::t('statuses', 'Statuses Doc Type'),
            'name' => Yii::t('statuses', 'Statuses Name'),
            'description' => Yii::t('statuses', 'Statuses Description'),
            'symbolic_id' => Yii::t('statuses', 'Statuses Symbolic Id'),
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
     * Doc types labels
     * 
     * @return array
     */
    public function docTypeLabels() {
        
        return StatusesDoctypes::createDropdown();
        
    }
    
    /**
     * @return string (doc_type label) || integer (doc_type) || null
     */
    public function getDocTypeName() {
        
        $list = $this->docTypeLabels();
        
        if( !empty($list) && isset($list[$this->doc_type]) ) return $list[$this->doc_type];
        return $this->doc_type;
        
    }

    public static function getAvailableStatuses($doc)
    {
        return static::find()->where(['doc_type' => $doc->id])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne( StatusesDoctypes::className(), ['id' => 'doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusesLinks()
    {
        return $this->hasMany(StatusesLinks::className(), ['status_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusesLinks0()
    {
        return $this->hasMany(StatusesLinks::className(), ['status_to' => 'id']);
    }
    
    /**
     * @inheritdoc
     */
    public static function returnStatusId( $symbolicId )
    {
        $status = static::find()->where(['symbolic_id' => $symbolicId])->one();
        if( $status ) return $status->id;
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return $this->docTypeName . ' - ' . $this->symbolic_id . ' - ' . $this->name;
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert,$attr)
    {
        /*
        if($insert) {
            
        }
        */
        parent::afterSave($insert,$attr);
    }

}
