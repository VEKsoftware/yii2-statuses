<?php

namespace statuses;

use yii\base\ErrorException;

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
     * Class for access methods, implements StatusesAccessInterface
     */
    public $accessClass;
    
    /**
     * Class for access rights from DB
     */
    public $accessRightsClass;
    
    /**
     * Doc types list [ $id => $label ]
     */
    public $docTypes = [];
    
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
    
    /**
     * 
     */
    protected function checkAccessClassConfig() {
        
        $reflectionClass = new \ReflectionClass( $this->accessClass );
        
        if( $reflectionClass->implementsInterface('\statuses\StatusesAccessInterface') == false ) {
            throw new ErrorException('Statuses::accessClass must implement StatusesAccessInterface.');
        }
        
    }
    
    /**
     * 
     */
    protected function checkAccessRightsClassConfig() {
        
        return true;
        
    }

}
