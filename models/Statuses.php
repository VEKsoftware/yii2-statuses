<?php

namespace statuses\models;

use statuses\components\CommonRecord;
use Yii;
use yii\base\InvalidParamException;
use yii\db\ActiveQueryInterface;
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
 * @property string docTypeName
 * @property string symbolic_id
 * @property string fullName
 */
class Statuses extends CommonRecord
{
    private static $_statuses;


    /**
     * Возвращает ID статуса для заданой категории документа и символьным алиасом статуса
     * @param string $docTypeAlias Идентификатор типа документа
     * @param string $statusAlias Идентификатор статуса
     * @return null|integer
     */
    public static function id($docTypeAlias, $statusAlias)
    {
        $docType = StatusesDoctypes::findOne(['symbolic_id' => $docTypeAlias]);
        if(isset($docType)) {
            /** @var StatusesDoctypes $docType */
            $list = ArrayHelper::index(static::listStatuses($docType->symbolic_id), 'symbolic_id');
            if(isset($list[$statusAlias])) {
                return $list[$statusAlias]->id;
            } else {
                throw new InvalidParamException('Status not found');
            }
        } else {
            throw new InvalidParamException('DocType not found');
        }
    }

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
     * @internal param string|string[] $symbolicId The symbolic tags of the rights
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
     * @param string $docTypeId The symbolic id of the document type
     *
     * @return static[]
     */
    public static function listStatuses($docTypeId)
    {
        if (!isset(static::$_statuses[$docTypeId])) {
            static::$_statuses[$docTypeId] = static::findStatuses($docTypeId)->all();
        }

        return static::$_statuses[$docTypeId];
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
            ['symbolic_id', 'unique',],
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

    public function getAvailableStatuses($rightIds = null)
    {
        return $this->hasMany(self::className(), ['id' => 'status_to'])
            ->via('statusesLinksFrom', function ($q) use ($rightIds) {
                /** @var ActiveQueryInterface $q */
                $q->andFilterWhere(['right_tag' => $rightIds]);
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
