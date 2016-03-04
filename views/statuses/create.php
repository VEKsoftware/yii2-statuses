<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model statuses\models\Statuses */

$this->title = Yii::t('statuses', 'Create Statuses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statuses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
