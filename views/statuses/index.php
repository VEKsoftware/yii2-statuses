<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel statuses\models\StatusesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('statuses', 'Statuses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statuses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('statuses', 'Create Statuses'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_type',
                'filter' => $searchModel->docTypeLabels(),
                'value' => function ($model) {

                    return $model->docTypeName;

                },
            ],
            'symbolic_id',
            'name',
            'description',
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model, $key) {
                    return Html::a(Yii::t('statuses', 'View Statuses'), ['view', 'id' => $key]);
                },
            ],
        ],
    ]); ?>

</div>
