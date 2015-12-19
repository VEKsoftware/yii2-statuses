<?php
namespace statuses\components;

use Yii;
use yii\db\ActiveRecord;
use yii\base\ErrorException;

use statuses\Statuses;

class CommonRecord extends ActiveRecord
{
    public static function getDb() {
//        return Yii::$app->db_common;
        $instance = Statuses::getInstance();
        if($instance === NULL) {
            throw new ErrorException('You should use this class through yii2-wallets module.');
        } elseif(!$instance->db) {
            $db = 'db';
        } else {
            $db = $instance->db;
        }
        return Yii::$app->get($db);
    }
}
