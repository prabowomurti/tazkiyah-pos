<?php

use yii\helpers\Html;
use common\components\widgets\ZeedActiveForm;

use kartik\datetime\DateTimePicker;
use common\components\widgets\GridView;
use yii\widgets\Pjax;
use common\models\Order;
use common\models\Outlet;
use common\models\Product;
use common\models\ProductAttribute;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ZeedActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_id')->label(Yii::t('app', 'Customer Name (Phone)'))->dropdownList(Customer::getAllAsList(), ['prompt' => Yii::t('app', 'Select Customer')]) ?>

    <?= $form->field($model, 'outlet_id')->label('Outlet')->dropdownList(Outlet::getAllAsList(), ['prompt' => Yii::t('app', 'Select Outlet')]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'tax')->textInput(['type' => 'number', 'step' => 0.01]) ?>

    <?= $form->field($model, 'discount')->textInput(['type' => 'number', 'step' => 0.01]) ?>

    <?= $form->field($model, 'total_price')->textInput(['type' => 'number', 'step' => 0.01, 'id' => 'total_price']) ?>

    <?= $form->field($model, 'status')->dropdownList(Order::getStatusAsList(), ['prompt' => Yii::t('app', 'Select Status')]) ?>

    <?= $form->field($model, 'delivery_time')->widget(DateTimePicker::classname(), [
        'type' => DateTimePicker::TYPE_INPUT,
        'pluginOptions' => [
            'startDate' => date('Y-m-d H:i:s'),
            'format' => 'yyyy-mm-dd hh:ii:00',
        ],
    ]);?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::label(Yii::t('app', 'Order Items'), 'order-items', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) ?>
        <div class="col-md-7 col-sm-6 col-xs-12 ">
            <?php if ($model->isNewRecord) : ?>
            <label class="control-label" style="font-weight: normal"><?= Yii::t('app', 'Please add order before add product/items');?></label>
            <?php else : ?>
                <span class="btn btn-default btn-xs" data-toggle="modal" data-target="#add_new_product_modal" style="margin-top:7px">
                    <span class="glyphicon glyphicon-plus-sign"> </span> <?= Yii::t('app', 'Add Product');?>
                </span>
                <!-- <span class="btn btn-default btn-xs recalculate_total_price" style="margin-top:7px" data-url="/order-item/recalculate?id=<?= $model->id?>">
                    <span class="glyphicon glyphicon-refresh"> </span> <?= Yii::t('app', 'Recalculate Total Price');?>
                </span> -->

            <?php endif;?>

        </div>
    </div>

    <?php if ( ! $model->isNewRecord) : ?>
    <div class="form-group">
        <div class="col-md-12 col-sm-6 col-xs-12 order_items">
        <?php Pjax::begin(['id' => 'order-items-list', 'enablePushState' => false, 'timeout' => false]); ?>
            <?= GridView::widget([
                'dataProvider' => $orderItemsProvider,
                'tableOptions' => ['class' => 'table table-hover table-striped table-bordered', 'style' => 'margin-bottom: 0px'],
                'summary' => false,
                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '&mdash;'],
                'columns' => [
                    'id',
                    'product_label',
                    [
                        'label' => Yii::t('app', 'Attribute'),
                        'value' => function ($model) {return $model->productAttribute ? $model->productAttribute->attributeCombinationLabel : null;}
                    ],
                    'quantity',
                    'unit_price',
                    'discount',
                    [
                        'label' => 'Sub Total',
                        'value' => function ($model) {
                            return $model->quantity * $model->unit_price - $model->discount;
                        }
                    ],
                    'note',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'order-item',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url)
                            {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    false,
                                    [
                                        'class'          => 'order-items-update',
                                        'pjax-container' => 'order-items-list',
                                        'data-pjax'      => 'order-items-list',
                                        'title'          => Yii::t('app', 'Edit'),
                                        'style'          => 'cursor: pointer',
                                        'data-target'    => '#edit_order_items_modal',
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
                                        'class'          => 'order-items-delete',
                                        'delete-url'     => $url,
                                        'pjax-container' => 'order-items-list',
                                        'data-pjax'      => 'order-items-list',
                                        'title'          => Yii::t('app', 'Delete'),
                                        'style'          => 'cursor: pointer'
                                    ]
                                );
                            }
                        ]
                    ]
                ],
            ]); ?>
            <?= $this->registerJsFile('@web/js/order_items.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
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

<div class="modal fade" id="add_new_product_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Add New Order Item</h4>
            </div>
            <form class="form form-horizontal" id="add_new_product_form" data-url="/order-item/create">
                <?= Html::hiddenInput('OrderItem[order_id]', $model->id);?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="product_label">Product</label>
                        <div class="col-md-6">
                            <?= Html::dropdownList('OrderItem[product_id]', '', Product::getAllAsList(), [
                                'id' => 'product_dropdownlist',
                                'class' => 'form-control',
                                'prompt' => Yii::t('app', 'Select Product'),
                                'onchange' => '$.post("' . Yii::$app->urlManager->createUrl('product-attribute/list?id=').
                                    '"+$(this).val(),function( data ) 
                                    {
                                        $("select#product_attribute_dropdownlist").html( data );
                                    });'
                                ]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="product_attribute">Attribute</label>
                        <div class="col-md-6">
                            <?= Html::dropdownList('OrderItem[product_attribute_id]', '', [], [
                                'id' => 'product_attribute_dropdownlist',
                                'class' => 'form-control',
                                'prompt' => Yii::t('app', 'Select Product Attribute')
                                ]);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="quantity">Quantity</label>
                        <div class="col-md-6">
                            <?= Html::textInput('OrderItem[quantity]', 1, ['id' => 'product_quantity', 'class' => 'form-control', 'type' => 'number', 'step' => 0.01, 'min' => 1]);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="discount">Discount</label>
                        <div class="col-md-6">
                            <?= Html::textInput('OrderItem[discount]', 0, ['id' => 'discount', 'class' => 'form-control', 'type' => 'number', 'step' => 0.01]);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="note">Note</label>
                        <div class="col-md-6">
                            <?= Html::textInput('OrderItem[note]', '', ['class' => 'form-control']);?>
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

<div class="modal fade" id="edit_order_items_modal" tabindex="-2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span></span>Edit Order Item</h4>
            </div>
            <form class="form form-horizontal" id="edit_order_item_form" data-url="/order-item/update?id=">
                <?= Html::hiddenInput('OrderItem[id]', '', ['id' => 'edit_order_item_form_id']);?>
            <div class="modal-body">

                <div class="form-group">
                    <label class="control-label col-md-4" for="order_item_quantity">Quantity</label>
                    <div class="col-md-6">
                        <?= Html::textInput('OrderItem[quantity]', 0, ['id' => 'edit_order_item_form_quantity', 'class' => 'form-control', 'type' => 'number', 'step' => 0.01, 'min' => 0.01]);?>
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