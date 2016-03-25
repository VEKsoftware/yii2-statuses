<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model statuses\models\Statuses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="statuses-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'doc_type')->dropDownList($model->docTypeLabels()); ?>

    <?= $form->field($model, 'symbolic_id')
        ->textInput(['maxlength' => true])
        ->hint(Yii::t('statuses', 'Allowed symbols: a-Z, dot, underscore, dash'));
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'description')->textarea(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('statuses', 'Create') : Yii::t('statuses', 'Refresh'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
