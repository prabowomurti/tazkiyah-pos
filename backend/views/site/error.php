<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="text-center">
    <h1 class="error-number"><?= $name?></h1>
    <p><?= $message;?></p>
    <a href="/">Go Home</a>
</div>
