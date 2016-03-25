<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model statuses\models\Statuses */

$this->title = $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('statuses', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statuses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <div class="row">
        <div class="col-xs-6">
            <?= Html::a(Yii::t('statuses', 'Update Statuses'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-xs-6 text-right">
            <?= Html::a(Yii::t('statuses', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('statuses', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'doc_type',
                'format' => 'raw',
                'value' => $model->docTypeName,
            ],
            'symbolic_id',
            'name',
            'description',
        ],
    ]) ?>

</div>
<div class="statuses-index">

    <h3><?= Html::encode(Yii::t('statuses', 'Statuses Links')) ?></h3>

    <p>
        <?= Html::a(Yii::t('statuses', 'Create Statuses Link'), ['link-create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'statusName',
            'right_tag',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return;
                    },
                    'update' => function ($url, $model, $key) {
                        return;
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Yii::t('statuses', 'Delete'), $url, [
                            'data' => [
                                'confirm' => Yii::t('statuses', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    /** @var \statuses\models\StatusesLinks $model */
                    return Url::toRoute(['statuses/link-delete',
                        'status_from' => $model->status_from,
                        'status_to' => $model->status_to,
                        'right_tag' => $model->right_tag,
                    ]);
                },
            ],
        ],
    ]); ?>

</div>
