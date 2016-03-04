<?php

namespace statuses;

use yii\base\ErrorException;

/**
 * Main class for Statuses module.
 *
 * @prop string|array signedUp The route to go after successful login
 */
class Statuses extends \yii\base\Module
{
    /**
     * @inherit
     */
    public $controllerNamespace = 'statuses\controllers';

    /**
     * Current project ID.
     */
    public $project_id = 2;

    /**
     * Database component to use in the module.
     */
    public $db;

    /**
     * Class for access methods, implements StatusesAccessInterface.
     */
    public $accessClass;

    /**
     * Class for access rights from DB (and search rights).
     */
    public $accessRightsClass;
    public $accessRightsSearchClass;

    /**
     * @inherit
     */
    public function init()
    {
        parent::init();

        $this->checkAccessClassConfig();
        $this->checkAccessRightsClassConfig();

        $this->registerTranslations();
    }

    /**
     * Initialization of the i18n translation module.
     */
    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['statuses'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@statuses/messages',

            'fileMap' => [
                'statuses' => 'statuses.php',
            ],

        ];
    }

    /**
     * 
     */
    protected function checkAccessClassConfig()
    {
        $reflectionClass = new \ReflectionClass($this->accessClass);

        if ($reflectionClass->implementsInterface('\statuses\StatusesAccessInterface') == false) {
            throw new ErrorException('Statuses::accessClass must implement StatusesAccessInterface.');
        }
    }

    /**
     * 
     */
    protected function checkAccessRightsClassConfig()
    {

        // check property 'accessRightsClass'

        $rightsClass = $this->accessRightsClass;
        $rights = new $rightsClass();

        if (!is_subclass_of($rights, '\yii\db\ActiveRecord', false)) {
            throw new ErrorException('Statuses::accessRightsClass must be extended from class \yii\db\ActiveRecord.');
        }

        try {
            $rights->id;
            $rights->name;
        } catch (\yii\base\UnknownPropertyException $e) {
            throw new ErrorException('Statuses::accessRightsClass must have properties: id, name.');
        }

        // check property 'accessRightsSearchClass'

        $rightsSearchClass = $this->accessRightsSearchClass;
        $rightsSearch = new $rightsSearchClass();

        if (!is_subclass_of($rightsSearch, $this->accessRightsClass, false)) {
            throw new ErrorException('Statuses::accessRightsSearchClass must be extended from class '.$this->accessRightsClass.'.');
        }

        $reflectRightsSearch = new \ReflectionClass($this->accessRightsSearchClass);
        if (!$reflectRightsSearch->hasMethod('search')) {
            throw new ErrorException('Statuses::accessRightsSearchClass must have public method "search( array $params )".');
        }

        return true;
    }
}
