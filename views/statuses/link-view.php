<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel statuses\models\StatusesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('statuses', 'View Statuses Links') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="statuses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('statuses', 'Create Statuses Link'), ['link-create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'status_to',
            ],
            [
                'attribute' => 'right_id',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function( $url, $model, $key ) { return null; },
                    'update' => function( $url, $model, $key ) { return null; },
                    'delete' => function( $url, $model, $key ) {
                        return Html::a( Yii::t('statuses', 'Delete'), $url);
                    }
                ],
                'urlCreator' => function($action, $model, $key, $index) {
                    return Url::toRoute(['statuses/statuses/link-delete',
                        'status_from' => $model->status_from,
                        'status_to' => $model->status_to,
                        'right_id' => $model->right_id,
                    ]);
                },
            ],
        ],
    ]); ?>

</div>
