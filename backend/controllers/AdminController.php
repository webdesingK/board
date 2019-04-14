<?php

namespace backend\controllers;
use common\models\Categories;
use yii\web\Controller;


class AdminController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionTreeManager() {

        $model = new Categories();



        return $this->render('tree-manager', compact(
            'model'
        ));
    }

//    public function actionRoot() {
//        $model = new Categories(['name' => 'Категории']);
//        $model->makeRoot();
//
//    }

}
