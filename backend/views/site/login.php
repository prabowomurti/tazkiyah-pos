<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <h1>Login Form</h1>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email'])->label(false); ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false); ?>

    <div>
        <?= Html::submitButton('Log In', ['class' => 'btn btn-default', 'name' => 'login-button', 'style' => 'margin: 10px 50px 0px 0px; font-size: 12px']) ?>
        <?= Html::a(Yii::t('app', 'Lost your password?'), Yii::$app->urlManager->createUrl('/site/request-password-reset'));?>
    </div>

    <div class="clearfix"></div>

    <div class="separator">
        <p class="change_link">New to site?
            <a href="#signup" class="to_register"> Create Account </a>
        </p>

        <div class="clearfix"></div>
        <br />

        <div>
            <h1><i class="fa fa-leaf"></i> <?= Yii::$app->name;?></h1>
            <p>&copy;2017 All Rights Reserved.</p>
        </div>
    </div>

<?php ActiveForm::end(); ?>
        
