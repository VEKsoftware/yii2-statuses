<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel statuses\models\StatusesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('statuses', 'Create Statuses Link') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'View Statuses Links'), 'url' => ['link-view']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="statuses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            
            <h3><?php echo Html::encode('Доступные статусы'); ?></h3>
            
            <?= GridView::widget([
                'dataProvider' => $dataProviderModel,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'attribute' => 'name',
                    ],
                ],
            ]); ?>
    
        </div>
        <div class="col-xs-6">
            
            <h3><?php echo Html::encode('Доступные права'); ?></h3>
            
            <?= GridView::widget([
                'dataProvider' => $rightsDataProvider,
                'filterModel' => $rightsSearchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    [
                        'attribute' => 'name',
                    ],
                ],
            ]); ?>
    
        </div>
    </div>
    
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('statuses', 'Create'), ['class' => 'btn btn-primary']); ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
