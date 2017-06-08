<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->input('email', ['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Leave this field blank if you do not want to change the password', 'minlength' => 6]) ?>

    <?= $form->field($model, 'role')->dropDownList(User::getRoleAsArray()); ?>

    <?= $form->field($model, 'status')->dropDownList(User::getStatusAsArray()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
