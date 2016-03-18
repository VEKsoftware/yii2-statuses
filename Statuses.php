<?php

namespace statuses;

use yii\base\ErrorException;
use yii\base\Module;
use yii\db\Connection;

/**
 * Main class for Statuses module.
 */
class Statuses extends Module
{
    /**
     * @inherit
     */
    public $controllerNamespace = 'statuses\controllers';

    /**
     * Class for access methods, implements StatusesAccessInterface.
     */
    public $accessClass;

    /** @var array $allScenarios Массив настроек для настроек прав доступа по ролям */
    public static $allScenarios = [
        'label' => 'Модуль статусов',
        'items' => [
            'statuses.statuses.create' => [
                'value' => 'statuses.statuses.create',
                'label' => 'Создавать статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.statuses.view' => [
                'value' => 'statuses.statuses.view',
                'label' => 'Просматривать статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.statuses.update' => [
                'value' => 'statuses.statuses.update',
                'label' => 'Редактировать статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.statuses.delete' => [
                'value' => 'statuses.statuses.delete',
                'label' => 'Удалять статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],


            'statuses.doctypes.create' => [
                'value' => 'statuses.doctypes.create',
                'label' => 'Создавать doctypes',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.doctypes.view' => [
                'value' => 'statuses.doctypes.view',
                'label' => 'Просматривать doctypes',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.doctypes.update' => [
                'value' => 'statuses.doctypes.update',
                'label' => 'Редактировать doctypes',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.doctypes.delete' => [
                'value' => 'statuses.doctypes.delete',
                'label' => 'Удалять doctypes',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],

            'statuses.statuses.link.create' => [
                'value' => 'statuses.statuses.link.create',
                'label' => 'Создавать ?ссылку? статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
            'statuses.statuses.link.delete' => [
                'value' => 'statuses.statuses.link.delete',
                'label' => 'Удалять ?ссылку? статусы',
                'items' => [
                    [
                        'value' => 'any',
                        'label' => 'Все',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @inherit
     */
    public function init()
    {
        parent::init();
        $this->checkAccessClassConfig();
        $this->registerTranslations();
    }

    /**
     * @inherit
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => self::getInstance()->accessClass,
            ],
        ];
    }

    /**
     *
     */
    protected function checkAccessClassConfig()
    {
        $reflectionClass = new \ReflectionClass($this->accessClass);
        if ($reflectionClass->implementsInterface('\statuses\StatusesAccessInterface') === false) {
            throw new ErrorException('\statuses\Statuses::$accessClass must implement \statuses\StatusesAccessInterface.');
        }
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
}
