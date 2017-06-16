<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;

use common\models\Attribute;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->title = Yii::t('app', 'Generate Attribute for ') . $model->label;
?>

<div class="item-attribute-generator">

    <div class="row">
        <div class="col-xs-12 col-sm-3">
            
            <h4>Attributes</h4>
            <?=
            Html::dropDownList(
                'ProductAttribute', 
                '', 
                array_filter(Attribute::getTree()), 
                [
                    'id'       => 'product_attribute_dropdown',
                    'class'    => 'form-control',
                    'multiple' => 'multiple', 
                    'style'    => 'width : 100%; height : 360px; margin-bottom: 8px',
                ]);?>
            <div class="btn btn-primary btn-sm btn_add_attribute">Add</div>
        </div>

        <div class="col-xs-12 col-sm-9">
            
            <h4>Attribute Combination</h4>

            <span class="info"><?= Yii::t('app', 'Please select some attributes on the left to combine, and click Add button');?></span>

            <?php $form = ActiveForm::begin(['id' => 'product-attribute-form',]); ?>

            <?= Html::hiddenInput('product_id', $model->id);?>

            <div class="form-group generate_button">
                <?= Html::submitButton('Generate', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>

</div>

<?= $this->registerJsFile('@web/js/attribute_generator.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
