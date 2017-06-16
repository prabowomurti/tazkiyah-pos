<?php

use yii\helpers\Html;
use common\components\widgets\ZeedActiveForm;

use common\models\Attribute;

/* @var $this yii\web\View */
/* @var $model common\models\Attribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attribute-form">

    <?php $form = ZeedActiveForm::begin(); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-attribute-parentId">
        <?= Html::label('Parent', 'parent', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12']) ?>
        <div class="col-sm-6 col-md-7 col-xs-12">
            <?= Html::dropDownList(
                    'Attribute[parentId]',
                    $model->parentId, 
                    Attribute::getTree($model->id), 
                    ['prompt' => 'No Parent (saved as root)', 'class' => 'form-control']);?>
        </div>
    </div>

    <?= $form->field($model, 'position')->textInput(['type' => 'number']) ?>

    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-7 col-sm-6 col-xs-12 col-md-offset-3">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ZeedActiveForm::end(); ?>

</div>
