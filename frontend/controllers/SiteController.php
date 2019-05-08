<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Categories;


class SiteController extends Controller {

    public function actionIndex() {

        $model = new Categories();

        return $this->render('index', compact('model'));
    }
}

