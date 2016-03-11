<?php

namespace statuses\components;

use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;

class CommonRecord extends ActiveRecord
{
    public static function getDb()
    {
        /** @var \statuses\Statuses $instance */
        $instance = \statuses\Statuses::getInstance();
        if ($instance === null) {
            throw new ErrorException('You should use this class through yii2-status module.');
        } elseif (!$instance->db) {
            $db = 'db';
        } else {
            $db = $instance->db;
        }

        return Yii::$app->get($db);
    }
}
