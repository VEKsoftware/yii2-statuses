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
            [['doc_type','name'], 'required'],
            [['doc_type'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200]
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
    public function afterSave($insert,$attr)
    {
        /*
        if($insert) {
            
        }
        */
        parent::afterSave($insert,$attr);
    }

}
