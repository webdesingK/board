<?php

namespace frontend\controllers;

use yii\web\Controller;

class SiteController extends Controller {

    public function actionIndex() {

        $id = \Yii::$app->request->get('id');

        return $this->render('index', [
            'id' => $id
        ]);
    }

}

