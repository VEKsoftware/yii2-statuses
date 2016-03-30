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


    public function getStatus()
    {

    }

    public function setStatus($value)
    {

    }

}