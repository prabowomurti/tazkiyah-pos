<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;

$bundle = common\components\assets\Asset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="/css/operator.css" rel="stylesheet">
    <?= $this->registerJsFile('@web/js/operator.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
    <?= $this->registerJsFile('@web/js/printThis.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="container">
    <div class="main_container">
        <!-- .left-menu -->
        <div class="col-lg-1 col-md-4 col-sm-12 col-xs-12 left-menu">
            <div class="nav nav-tabs">
                <div class="operator-menu col-lg-12 col-md-12 col-sm-4 col-xs-4">
                    <a href="#home" data-toggle="tab" aria-expanded="false">
                        <span class="fa fa-home fa-3x"></span><br />
                        Home
                    </a>
                </div>
                <div class="operator-menu col-lg-12 col-md-12 col-sm-4 col-xs-4">
                    <a href="#customer" data-toggle="tab" aria-expanded="false">
                        <span class="fa fa-user fa-3x"></span><br />
                        Customer
                    </a>
                </div>
                <div class="operator-menu col-lg-12 col-md-12 col-sm-4 col-xs-4 active">
                    <a href="#order" data-toggle="tab" aria-expanded="false">
                        <span class="fa fa-shopping-cart fa-3x"></span><br />
                        Order
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 operator-status">
                    <span class="fa fa-align-justify"></span><span id="current_time" class="pull-right"></span>
                </div>
            </div>
        </div>
        <!-- /.left-menu -->

        <div class="col-lg-11 col-md-8 col-sm-12 col-xs-12 right-content tab-content">
            <?= $content;?>
        </div>
    </div>
</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
