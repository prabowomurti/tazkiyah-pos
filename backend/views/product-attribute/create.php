<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductAttribute */

$this->title = Yii::t('app', 'Create Product Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
