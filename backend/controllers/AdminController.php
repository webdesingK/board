<?php

namespace backend\controllers;
use yii\web\Controller;


class AdminController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionTreeManager() {
        return $this->render('tree-manager');
    }

}
