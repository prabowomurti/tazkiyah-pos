<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\User;
use common\models\Outlet;

/* @var $form yii\widgets\ActiveForm */
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update Profile');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?> </h1>
    <?= $success ? 
    yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => 'Profile updated.',
    ]) : ''?>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Leave this field blank if you do not want to change the password', 'minlength' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
