<?php

    use yii\web\UrlNormalizer;

    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );

    return [
        'id' => 'app-adminPanel',
        'basePath' => dirname(__DIR__),
        'controllerNamespace' => 'adminPanel\controllers',
        'bootstrap' => ['log'],
        'modules' => [],
        'defaultRoute' => 'main',
        'components' => [
            'request' => [
                'csrfParam' => '_csrf-adminPanel',
                'baseUrl' => '/admin-panel',
                'enableCsrfValidation' => false,
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser',
                ]
            ],
            'user' => [
                'identityClass' => 'common\models\User',
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-adminPanel', 'httpOnly' => true],
            ],
            'session' => [
                // this is the name of the session cookie used for login on the adminPanel
                'name' => 'advanced-adminPanel',
            ],
//        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                ],
//            ],
//        ],
            'errorHandler' => [
                'errorAction' => 'main/error',
            ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'normalizer' => [
                    'class' => 'yii\web\UrlNormalizer',
                    'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY
                ],
            ],
            'assetManager' => [
                'bundles' => [
                    'adminPanel\assets\AppAsset' => [
                        'skin' => 'skin-purple',
                    ],
                ],
            ],
        ],
        'name' => 'AdminPanel',
        'params' => $params,
    ];
