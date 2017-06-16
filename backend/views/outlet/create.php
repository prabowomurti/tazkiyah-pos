<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Outlet */

$this->title = Yii::t('app', 'Create Outlet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Outlets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outlet-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
