Yii2-Statuses
================================
Yii2-Statuses - модуль предназначенный для управления статусами объектов.
Модуль реализует функционал контроля доступа 

Установка
------------------------------------------------------------
Проще всего установить модуль используя [composer](http://getcomposer.org/download/).

Запустите команду
```
php composer.phar require --prefer-dist VEKsoftware/yii2-statuses "*"
```
или добавьте
```
"VEKsoftware/yii2-statuses": "*"
```
в секцию `require` вашего `composer.json` файла.


Не забудьте добавить модуль в конфигурацию вашего проекта.
```
'statuses' => [
    'class' => 'statuses\Statuses',
    'db' => 'db_common',
    'accessClass' => 'common\behaviors\Access',
    'accessRightsClass' => 'partneruser\models\RefRights',
    'accessRightsSearchClass' => 'partneruser\models\RefRightsSearch',
],
```


Использование
------------------------------------------------------------
