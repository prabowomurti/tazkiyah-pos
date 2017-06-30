<?php

use yii\helpers\Html;
use common\components\widgets\ZeedActiveForm;
use common\components\widgets\GridView;

use common\models\Product;
use common\models\Attribute;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ZeedActiveForm::begin(); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => 0.01]) ?>

    <?= $form->field($model, 'visible')->dropdownList(Product::getVisibleAsList(), ['prompt' => 'Select visibility']) ?>

    <?= $form->field($model, 'position')->textInput(['type' => 'number']) ?>

    <div class="form-group">
        <?= Html::label(Yii::t('app', 'Product Attributes'), 'product-attribute', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) ?>
        <div class="col-md-7 col-sm-6 col-xs-12 ">
            <?php if ($model->isNewRecord) : ?>
            <label class="control-label" style="font-weight: normal"><?= Yii::t('app', 'Please add product before the attributes');?></label>
            <?php else : ?>
                <label>
                    <a class="btn btn-default btn-xs" href="<?=Yii::$app->urlManager->createUrl(['product-attribute/generator', 'product_id' => $model->id])?>">
                        <span class="glyphicon glyphicon-list-alt"> </span> <?= Yii::t('app', 'Go to Generator Menu')?>
                    </a>
                    <span class="btn btn-default btn-xs" data-toggle="modal" data-target="#add_new_attributes_modal">
                        <span class="glyphicon glyphicon-plus-sign"> </span> <?= Yii::t('app', 'Add Attribute');?>
                    </span>
                </label>
            
            <?php endif;?>

        </div>
    </div>

    <?php if ( ! $model->isNewRecord) : ?>
    <div class="form-group">
        <div class="col-md-7 col-sm-6 col-xs-12 col-md-offset-3 product_attributes">
        <?php Pjax::begin(['id' => 'product-attribute-list', 'enablePushState' => false, 'timeout' => false]); ?>
            <?= GridView::widget([
                'dataProvider' => $productAttributeProvider,
                'tableOptions' => ['class' => 'table table-hover table-striped table-bordered'],
                'summary' => false,
                'columns' => [
                    'id',
                    'attributeCombinationLabel',
                    [
                        'attribute' => 'price',
                        'value' => function ($model)
                        {
                            return ($model->price >= 0 ? '+' . $model->price : $model->price);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'product-attribute',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url)
                            {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    false,
                                    [
                                        'class'          => 'product-attribute-update',
                                        'pjax-container' => 'product-attribute-list',
                                        'data-pjax'      => 'product-attribute-list',
                                        'title'          => Yii::t('app', 'Edit'),
                                        'style'          => 'cursor: pointer',
                                        'data-target'    => '#edit_product_attributes_modal',
                                        'data-toggle'    => 'modal',
                                    ]
                                );
                            },
                            'delete' => function ($url) 
                            {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    false,
                                    [
                                        'class'          => 'product-attribute-delete',
                                        'delete-url'     => $url,
                                        'pjax-container' => 'product-attribute-list',
                                        'data-pjax'      => 'product-attribute-list',
                                        'title'          => Yii::t('app', 'Delete'),
                                        'style'          => 'cursor: pointer'
                                    ]
                                );
                            }
                        ]
                    ]
                ],
            ]); ?>
            <?= $this->registerJsFile('@web/js/product_attribute.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
    <?php endif;?>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-7 col-sm-6 col-xs-12 col-md-offset-3">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ZeedActiveForm::end(); ?>

</div>


<?php if ( ! $model->isNewRecord) :  // only show modal if update form?>

<div class="modal fade" id="add_new_attributes_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Add New Attribute</h4>
            </div>
            <form class="form form-horizontal" id="add_new_attribute_form" data-url="/product-attribute/create">
                <?= Html::hiddenInput('ProductAttribute[product_id]', $model->id);?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="product_attribute_label">Attribute(s)</label>
                        <div class="col-md-6">
                            <?= Html::listBox('attributes', '', Attribute::getTree(), ['id' => 'attribute_dropdownlist', 'class' => 'form-control', 'multiple' => 'multiple', 'size' => 10]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="product_attribute_price">Impact on Price</label>
                        <div class="col-md-6">
                            <?= Html::textInput('ProductAttribute[price]', 0, ['class' => 'form-control', 'type' => 'number', 'step' => 0.01]);?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Submit"/>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_product_attributes_modal" tabindex="-2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Product Attribute</h4>
            </div>
            <form class="form form-horizontal" id="edit_product_attribute_form" data-url="/product-attribute/update?id=">
                <?= Html::hiddenInput('ProductAttribute[id]', '', ['id' => 'edit_product_attribute_form_id']);?>
                <?= Html::hiddenInput('ProductAttribute[product_id]', $model->id);?>
            <div class="modal-body">

                <div class="form-group">
                    <label class="control-label col-md-4" for="product_attribute_price">Impact on Price</label>
                    <div class="col-md-6">
                        <?= Html::textInput('ProductAttribute[price]', 0, ['id' => 'edit_product_attribute_form_price', 'class' => 'form-control', 'type' => 'number', 'step' => 0.01]);?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Update"/>
            </div>
            </form>
        </div>
    </div>
</div>

<?php endif;?>