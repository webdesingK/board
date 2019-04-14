<?php

namespace backend\controllers;

class AdminController extends \yii\web\Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionTreeManager() {
        return $this->render('tree-manager');
    }

}
