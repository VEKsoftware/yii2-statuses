<?php

namespace statuses;

/**
 *
 * Main class for Statuses module.
 *
 * @prop string|array signedUp The route to go after successful login
 *
 */
class Statuses extends \yii\base\Module
{
    /**
     * @inherit
     */
    public $controllerNamespace = 'statuses\controllers';

    /**
     * Current project ID
     */
    public $project_id = 2;

    /**
     * Database component to use in the module
     */
    public $db;

    /**
     * @inherit
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    /**
     * Initialization of the i18n translation module
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

}
