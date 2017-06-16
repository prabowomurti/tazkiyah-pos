<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Outlet',
]) . $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Outlets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="outlet-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
