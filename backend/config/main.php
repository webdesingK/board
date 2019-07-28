<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'defaultRoute' => 'admin',
//    'layout' => 'admin',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/админка',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'admin/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'мененджер-категорий' => 'admin/categories-manager',
                'мененджер-городов' => 'admin/cities-manager',
                'создание-фильтров' => 'admin/create-filters',
                'редактирование-фильтров' => 'admin/edit-filters',
                'привязка-фильтров' => 'admin/bind-filters',
                'категории-фильтры' => 'admin/categories-filters',
                'фильтры-категории' => 'admin/filters-categories',
                'просмотр-городов' => 'cities/index',
                'просмотр-категорий' => 'categories/index'
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'backend\assets\AppAsset' => [
                    'skin' => 'skin-purple',
                ],
            ],
        ],
    ],
    'name' => 'Админка',
    'params' => $params,
];
