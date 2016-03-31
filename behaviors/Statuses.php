<?php

namespace statuses\behaviors;

use statuses\models\StatusesDoctypes;
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
 * @property \statuses\models\Statuses status
 */
class Statuses extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior
     */
    public $owner;
    public $statusIdField;

    private static $_docTypes = [];

    /**
     * Метод возвращает отношение связанного объекта статуса.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        // TODO странная бага, если изменить идентификатор статуса в самой модели и сохранить, то при повторном вызове геттера будет возвращен прошлый результат
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
        $docType = $this->getDocType($this->owner->docTypeSymbolicId);

        /** @var \statuses\models\Statuses $statusTo */
        $statusTo = ArrayHelper::getValue($docType->statuses, $statusSymbolicId);
        if (!isset($statusTo)) {
            throw new BadRequestHttpException("Status {$statusSymbolicId} not found.");
        }

        // Проверяем не пытается ли пользователь изменить статус с текущего на текущий
        if ($statusTo->id === $this->owner->{$this->statusIdField}) {
            return true;
        }

        // Получаем ссылки где status_to равен нашему $statusTo
        $linksTo = $statusTo->linksTo;

        /** @var StatusesLinks $link */
        foreach ($linksTo as $link) {
            // Отсеиваем только те статусы, где status_from совпадает с текущим установленным статусом
            if ($link->status_from === $this->owner->{$this->statusIdField}) {
                // Проверяем права доступа
                if ($this->owner->isAccessed($link->right_tag)) {
                    $this->owner->{$this->statusIdField} = $statusTo->id;
                    return true;
                }
            }
        }
        throw new BadRequestHttpException("You can't set status {$statusSymbolicId}.");
    }

    /**
     * @param string $symbolicId
     * @return StatusesDoctypes
     */
    public static function getDocType($symbolicId)
    {
        if (!isset(self::$_docTypes[$symbolicId])) {
            self::$_docTypes[$symbolicId] = StatusesDoctypes::findDoc($symbolicId);
        }
        return self::$_docTypes[$symbolicId];
    }

    public function setStatusSafe($statusSymbolicId)
    {
        $docType = $this->getDocType($this->owner->docTypeSymbolicId);

        /** @var \statuses\models\Statuses $statusTo */
        $statusTo = ArrayHelper::getValue($docType->statuses, $statusSymbolicId);
        if (!isset($statusTo)) {
            throw new BadRequestHttpException("Status {$statusSymbolicId} not found.");
        }
        $this->owner->{$this->statusIdField} = $statusTo->id;
    }

}