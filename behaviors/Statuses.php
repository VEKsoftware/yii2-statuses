<?php

namespace statuses\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Behavior class for checking access to set status field and setting it if accessed.
 *
 * @property integer $statusField Название поля со статусом в таблице
 * @property integer $status
 */
class Statuses extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior
     */
    public $owner;
    public $statusField;

    /**
     * Метод возвращает отношение связанного объекта статуса.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->owner->hasOne(\statuses\models\Statuses::className(), ['id' => $this->statusField]);
    }

    /**
     * Сеттер для установки статуса, проверяет доступен ли назначаемый статус и устанавливает его.
     *
     * @param $value
     * @param bool $safe
     */
    public function setStatus($value, $safe = false)
    {
        if($safe) {

        } else {

        }

        $status = $this->getStatus()->one();

        if(isset($status)) {
            /** @var \statuses\models\Statuses $status */
            $status->get
        }
        var_dump($this->status->name);
        exit();
        $this->owner->{$this->statusField} = $value;

    }

}