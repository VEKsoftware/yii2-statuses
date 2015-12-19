<?php

namespace statuses\models;

use Yii;

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
    // List of doc types
    const DT_USERS = 1;
    const DT_PAYMENTS = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_type'], 'required'],
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
     * Doc types labels
     * 
     * @return array
     */
    public function docTypeLabels() {
        return [
            self::DT_USERS => 'Пользователи',
            self::DT_PAYMENTS => 'Платежи',
        ];
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
