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

        if (Yii::$app->request->isAjax) {
            dump(Yii::$app->request->get());die;
        }

        if ((isset($get['city']) && $get['city'] === 'Все-города') && !isset($get['category'])) $this->redirect('/', 301);

        if ((isset($url['city']) && $url['city'] == 'error') || (isset($url['category']) && $url['category'] == 'error')) {
            throw new NotFoundHttpException();
        }

        $adsData = Ads::getAdsData($url);

        return $this->render('index', [
            'url' => $url,
            'adsData' => $adsData
        ]);
    }


}

