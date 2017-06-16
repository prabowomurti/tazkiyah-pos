<?php

use yii\helpers\Html;
use common\components\widgets\ZeedActiveForm;

use kartik\datetime\DateTimePicker;
use common\models\Order;
use common\models\Outlet;
use common\models\Customer;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ZeedActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_id')->dropdownList(Customer::getAllAsList(), ['prompt' => Yii::t('app', 'Select Customer')]) ?>

    <?= $form->field($model, 'outlet_id')->label('Outlet')->dropdownList(Outlet::getAllAslist(), ['prompt' => Yii::t('app', 'Select Outlet')]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'tax')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'total_price')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->dropdownList(Order::getStatusAsList(), ['prompt' => Yii::t('app', 'Select Status')]) ?>

    <?= $form->field($model, 'delivery_time')->widget(DateTimePicker::classname(), [
        'type' => DateTimePicker::TYPE_INPUT,
        'pluginOptions' => [
            'startDate' => date('Y-m-d H:i:s'),
            'format' => 'yyyy-mm-dd hh:ii:00',
        ],
    ]);?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>


    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-7 col-sm-6 col-xs-12 col-md-offset-3">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ZeedActiveForm::end(); ?>

</div>
