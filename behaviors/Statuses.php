<?php

namespace statuses\behaviors;

use statuses\models\StatusesLinks;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * Behavior class for checking access to set status field and setting it if accessed.
 *
 * @property integer statusIdField Название поля со статусом в таблице
 * @property integer status
 */
class Statuses extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior
     */
    public $owner;
    public $statusIdField;

    /**
     * Метод возвращает отношение связанного объекта статуса.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->owner->hasOne(\statuses\models\Statuses::className(), ['id' => $this->statusIdField]);
    }

    /**
     * Сеттер для установки статуса, проверяет доступен ли назначаемый статус и устанавливает его.
     * Используйте setStatusSafe() если статус нужно установить без проверки прав доступа.
     *
     * @param string $statusSymbolicId
     * @return bool
     * @throws BadRequestHttpException
     */
    public function setStatus($statusSymbolicId)
    {
        /** @var Statuses $statusFrom */
        $statusFrom = $this->owner->status;
        $statusTo = \statuses\models\Statuses::findStatusBySymbolicId($statusSymbolicId);

        if ($statusTo && $statusFrom) {
            $links = StatusesLinks::findLinksByFromIdAndToId($statusFrom->id, $statusTo->id);
            $rightTags = ArrayHelper::getColumn($links, 'right_tag');
            foreach ($rightTags as $rightTag) {
                if ($this->owner->isAccessed($rightTag)) {
                    $this->owner->{$this->statusIdField} = $statusTo->id;
                    return true;
                }
            }
            throw new BadRequestHttpException("You can't set status {$statusSymbolicId}.");
        } else {
            throw new BadRequestHttpException("Status {$statusSymbolicId} not found.");
        }
    }

    public function setStatusSafe($value)
    {
        $this->owner->{$this->statusField} = $value;
    }

}