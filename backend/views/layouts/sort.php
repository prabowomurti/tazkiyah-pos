<?php

use yii\helpers\Html;
use common\components\widgets\ZeedSortable;

$this->title = Yii::t('app', 'Sort ' . $model_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model_name), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-sort">

    <h1><?= Html::encode($this->title) ?></h1>
<?php if (empty($items)) : ?>
    <p>No item</p>
<?php else : ?>
    <div>
        <span class="text-left">
            Drag and drop row to sort
        </span>
        <span class="pull-right">
            <div class="btn-group">
                <button class="btn btn-default" id="sort_label_asc_btn"><span class="glyphicon glyphicon-sort-by-alphabet"></span></button>
                <button class="btn btn-default" id="sort_label_desc_btn"><span class="glyphicon glyphicon-sort-by-alphabet-alt"></span></button>
            </div>
        </span>
    </div>
    <br />
    <div class="clearfix"></div>
    
    <?= ZeedSortable::widget(['items' => $items]);?>
    <?= $this->registerJsFile('@web/js/sortable_item.js', ['depends' => [\yii\web\JqueryAsset::className()]])?>

    <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'id' => 'position_order_btn', 'data-url' => Yii::$app->urlManager->createUrl(Yii::$app->controller->id . '/sort')]);?>
    <?= Html::button(Yii::t('app', 'Reset'), ['class' => 'btn btn-default', 'id' => 'reset_btn']);?>
    
<?php endif;?>
</div>

<style type="text/css">
    /* hack for outerwidth of helper*/
    tr.ui-sortable-helper {display: table;}
</style>
