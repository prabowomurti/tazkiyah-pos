<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Order Item',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
