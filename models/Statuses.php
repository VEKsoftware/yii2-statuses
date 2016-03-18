<?php

namespace statuses\models;

use statuses\components\CommonRecord;
use Yii;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "statuses".
 *
 * @property int $id
 * @property int $doc_type
 * @property string $name
 * @property string $description
 * @property StatusesLinks[] $statusesLinks
 * @property StatusesLinks[] $statusesLinks0
 * @property mixed docTypeName
 * @property mixed symbolic_id
 * @property mixed fullName
 */
class Statuses extends ActiveRecord
{
    private static $_statuses;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statuses}}';
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
     * {@inheritdoc}
     */
    public static function findByTag($symbolicId)
    {
        if (is_array($symbolicId)) {
            $status = static::find()->where(['symbolic_id' => $symbolicId])->all();
            if ($status) {
                return ArrayHelper::getColumn($status, 'id');
            }
        } else {
            $status = static::find()->where(['symbolic_id' => $symbolicId])->one();
            if ($status) {
                /** @var Statuses $status */
                return $status->id;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findByDocTypeTag($docType, $symbolicId)
    {
        $query = static::find()->joinWith('docType')->where(['{{statuses_doctypes}}.symbolic_id' => $docType]);

        if (is_array($symbolicId)) {
            $status = $query->andWhere(['{{statuses}}.symbolic_id' => $symbolicId])->all();

            if ($status) {
                return $status;
            }
        } else {
            $status = $query->andWhere(['{{statuses}}.symbolic_id' => $symbolicId])->one();
            if ($status) {
                return $status;
            }
        }

        return null;
    }

    /**
     * Find certain status by tag.
     *
     * @param string $docType The symbolic tag of the document type
     * @param string|string[] $symbolicId The symbolic tag of the status to search for
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findStatus($docType, $symbolicId)
    {
        return static::findStatuses($docType)
            ->andWhere(['[[statuses.symbolic_id]]' => $symbolicId]);
    }

    /**
     * Find all statuses for the specific doc type.
     *
     * @param string $docType The symbolic tag of the document type
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findStatuses($docType)
    {
        return static::find()
            ->joinWith('docType')
            ->where(['[[statuses_doctypes.symbolic_id]]' => $docType]);
    }

    /**
     * Find all statuses allowed by access rights.
     *
     * @param string $docType The symbolic tag of the document type
     * @param $rightId
     * @return \yii\db\ActiveQuery
     * @internal param string|\string[] $symbolicId The symbolic tags of the rights
     *
     */
    public static function findAvailableStatuses($docType, $rightId)
    {
        return static::findStatuses($docType)
            ->joinWith('statusesLinksTo')
            ->andWhere(['[[statusesLinksTo.status_from]]' => $rightId]);
    }

    /**
     * Return an array of all statuses for the specific doc type.
     *
     * @param string $docType The symbolic tag of the document type
     *
     * @return static[]
     */
    public static function listStatuses($docType)
    {
        if (!isset(static::$_statuses[$docType])) {
            static::$_statuses[$docType] = static::findStatuses($docType)->all();
        }

        return static::$_statuses[$docType];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_type', 'name', 'symbolic_id'], 'required'],
            [['doc_type'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['symbolic_id'], 'string'],
            [['doc_type', 'symbolic_id'], 'unique', 'targetAttribute' => ['doc_type', 'symbolic_id']],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(StatusesDoctypes::className(), ['id' => 'doc_type']);
    }

    /**
     * @return string (doc_type label) || integer (doc_type) || null
     */
    public function getDocTypeName()
    {
        $list = $this->docTypeLabels();

        if (!empty($list) && isset($list[$this->doc_type])) {
            return $list[$this->doc_type];
        }

        return $this->doc_type;
    }

    /**
     * Doc types labels.
     *
     * @return array
     */
    public function docTypeLabels()
    {
        return StatusesDoctypes::createDropdown();
    }

    public function getAvailableStatuses($rightIds = null) //($doc, $rightId)
    {
        return $this->hasMany(self::className(), ['id' => 'status_to'])
            ->via('statusesLinksFrom', function ($q) use ($rightIds) {
                /** @var ActiveQueryInterface $q */
                $q->andFilterWhere(['right_id' => $rightIds]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusesLinksFrom()
    {
        return $this->hasMany(StatusesLinks::className(), ['status_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusesLinksTo()
    {
        return $this->hasMany(StatusesLinks::className(), ['status_to' => 'id'])
            ->from(['statusesLinksTo' => StatusesLinks::tableName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName()
    {
        return $this->docTypeName . ' - ' . $this->symbolic_id . ' - ' . $this->name;
    }
}
