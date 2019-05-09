<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Categories;


class SiteController extends Controller {

    public $model;

    public function actionIndex() {

        $model = new Categories();
        $this->model = $model;

        return $this->render('index', compact('model'));
    }
}

