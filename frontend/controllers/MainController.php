<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use frontend\models\Ads;
use frontend\models\Cities;
use frontend\models\Categories;
use yii\web\NotFoundHttpException;
use frontend\components\urlParser;

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
        $url = urlParser::getArray($get);

        if (isset($get['city']) && $get['city'] == 'Все-города' && !isset($get['categoryFirstLvl'])) $this->redirect('/', 301);

        if ($url['city'] == 'error' || (isset($url['categories']) && $url['categories'] == 'error')) {
            throw new NotFoundHttpException();
        }

//        $adsData = Ads::getAdsData($url);

        $adsData = [];

//        if (Yii::$app->request->isAjax) {
//            return $this->renderPartial('index', [
//                'url' => $url,
//                'adsData' => $adsData
//            ]);
//        }

        return $this->render('index', [
            'url' => $url,
            'adsData' => $adsData,
        ]);
    }

    public function actionFilters() {

    }

}

