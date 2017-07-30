<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'user' => [
            // 'identityClass' => 'common\models\User',
            'identityClass' => 'api\modules\v1\models\Client',
            'enableAutoLogin' => false,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            // 'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'enableStrictParsing' => true,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/user'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/client'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/product'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/customer'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/order'],
            ],
        ],
    ],
    'params' => $params,
];
