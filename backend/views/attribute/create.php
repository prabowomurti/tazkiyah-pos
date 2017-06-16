<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Attribute */

$this->title = Yii::t('app', 'Create Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
