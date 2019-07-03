<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use frontend\models\Ads;
use frontend\models\Cities;
use frontend\models\Categories;
use yii\web\NotFoundHttpException;

class MainController extends Controller {

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {

        $get = Yii::$app->request->get();
        $url = [];

        if (isset($get['city']) && $get['city'] === 'Все-города' && !isset($get['category'])) $this->redirect('/', 301);

        $citiesUrl = Cities::getUrls();
        array_unshift($citiesUrl, 'Все-города');
        $categoriesUrl = Categories::getUrls();

        if (isset($get['city']) && !in_array($get['city'], $citiesUrl, true) || isset($get['category']) && !in_array($get['category'], $categoriesUrl, true)) {
            throw new NotFoundHttpException();
        }

        if (isset($get['city'])) {
            if ($get['city'] == 'Все-города') {
                $url['city'] = [
                    'name' => 'Все города',
                    'url' => 'Все-города'
                ];
            }
            else {
                $url['city'] = [
                    'name' => Cities::getNameByUrl($get['city']),
                    'url' => $get['city']
                ];
            }
        }

        if (isset($get['category'])) {
            $url['category'] = [
                'name' => Categories::getNameByUrl($get['category']),
                'url' => $get['category'],
            ];
        }

        return $this->render('index', [
            'url' => $url
        ]);
    }


}

