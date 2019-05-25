<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use frontend\models\Ads;
use frontend\models\Cities;
use frontend\models\Categories;

class SiteController extends Controller {

    public $urlCity;
    public $urlCategory;

    public function actionIndex() {

        $get = Yii::$app->request->get();

        $this->urlCity = isset($get['city']) ? $get['city'] : null;
        $this->urlCategory = isset($get['category']) ? $get['category'] : null;


        $idCity = $this->urlCity ? Cities::getIdByName($this->urlCity) : null;
        $idCategory = $this->urlCategory ? Categories::getIdByName($this->urlCategory) : null;

        $ads = new Ads;

        $adsData = $ads->getAdsData($idCity, $idCategory);

        return $this->render('index', [
            'adsData' => $adsData
        ]);
    }

}

