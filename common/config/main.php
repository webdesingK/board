<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
//            'appendTimestamp' => true,                 // временная метка для файлов ресурсов
//            'linkAssets' => true,                    // вкл ссылки на внешние файлы ресурсов
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js'
                    ]
                ]
            ]
        ],
    ],
];
