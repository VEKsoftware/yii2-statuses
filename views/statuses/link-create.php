<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel statuses\models\StatusesSearch */
/* @var $dataProviderModel yii\data\ActiveDataProvider */
/* @var \statuses\models\Statuses $model */
/* @var \statuses\models\StatusesLinks $modelLink */

$this->title = Yii::t('statuses', 'Create Statuses Link') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="statuses-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-5">
            <?php echo $form->field($modelLink, 'status_to', [
                'labelOptions' => ['style' => 'font-size: 150%;'],
            ])->hiddenInput(); ?>
            <?php echo $form->field($modelLink, 'status_from')->hiddenInput()->label(false) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProviderModel,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'id',
                        'label' => '',
                        'format' => 'raw',
                        'value' => function ($model, $key) {
                            return Html::radio('status_to', false, [
                                'value' => $key,
                                'onclick' => 'javascript:document.getElementById("statuseslinks-status_to").value = this.value;',
                            ]);
                        },
                        'options' => [
                            'width' => '30px',
                        ],
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'symbolic_id',
                    ],
                    [
                        'attribute' => 'name',
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-xs-5">
            <?php echo $form->field($modelLink, 'right_tag', [
                'labelOptions' => ['style' => 'font-size: 150%;'],
            ])->textInput()->hint(Yii::t('statuses', 'Allowed symbols: a-Z, dot, underscore, dash')); ?>
        </div>
        <div class="col-xs-2">
                <?php echo Html::submitButton(Yii::t('statuses', 'Create'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
