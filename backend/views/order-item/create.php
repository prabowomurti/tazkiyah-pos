<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = Yii::t('app', 'Create Order Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
