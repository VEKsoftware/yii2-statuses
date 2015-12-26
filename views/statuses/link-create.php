<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel statuses\models\StatusesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('statuses', 'Create Statuses Link') . ': ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'View Statuses Links'), 'url' => ['link-view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="statuses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-xs-2">
            <div class="form-group">
                <?php echo Html::submitButton(Yii::t('statuses', 'Create'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <div class="col-xs-5">
            <?php echo $form->field($modelLink, 'status_to', [
                'labelOptions' => [ 'style' => 'font-size: 150%;' ]
            ])->textInput(['style' => 'display: none;']); ?>
        </div>
        <div class="col-xs-5">
            <?php echo $form->field($modelLink, 'right_id', [
                'labelOptions' => [ 'style' => 'font-size: 150%;' ]
            ])->textInput(['style' => 'display: none;']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-10">
            <?php echo $form->field($modelLink, 'status_from', [
                'labelOptions' => [ 'style' => 'font-size: 0%; display: none;' ]
            ])->textInput(['style' => 'display: none;', 'readonly' => true]); ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-xs-2">
        </div>
        <div class="col-xs-5">
            
            <?= GridView::widget([
                'dataProvider' => $dataProviderModel,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'attribute' => 'id',
                        'label' => '',
                        'format' => 'raw',
                        'value' => function($model, $key) {
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
            
            <?= GridView::widget([
                'dataProvider' => $rightsDataProvider,
                'filterModel' => $rightsSearchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'attribute' => 'id',
                        'label' => '',
                        'format' => 'raw',
                        'value' => function($model, $key) {
                            return Html::radio('right_id', false, [
                                'value' => $key,
                                'onclick' => 'javascript:document.getElementById("statuseslinks-right_id").value = this.value;',
                            ]);
                        },
                        'options' => [
                            'width' => '30px',
                        ],
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'name',
                    ],
                ],
            ]); ?>
    
        </div>
    </div>

</div>
