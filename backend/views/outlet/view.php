<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Outlets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outlet-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'label',
            'address:ntext',
            'latitude',
            'longitude',
            'phone',
            [
                'attribute' => 'status',
                'value' => function ($model) {return empty($model->status) ? '' : \common\models\Outlet::getStatusAsList($model->status);}
            ]
        ],
    ]) ?>

</div>
