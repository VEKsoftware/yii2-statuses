<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model statuses\models\Statuses */

$this->title = Yii::t('statuses', 'Update {modelClass}: ', [
    'modelClass' => 'Statuses',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('statuses', 'Update');
?>
<div class="statuses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
