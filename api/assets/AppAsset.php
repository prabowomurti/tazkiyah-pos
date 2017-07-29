<?php

namespace api\assets;

use yii\web\AssetBundle;

/**
 * Main api application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];

    // include jQuery at the beginning of the page    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
