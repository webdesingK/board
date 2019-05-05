<?php

namespace backend\controllers;

use common\models\Categories;
use yii\web\Controller;
use Yii;

class AdminController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCategoriesManager() {

        $model = new Categories();
//        $arrDeactivatedId = $model->getDeactivateId();

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $result = null;
//            $arrDeactivatedId = null;
            $lastId = null;
            if ($post['nameOfAction'] == 'create') {
                $data = [
                    'id' => $post['id'],
                    'name' => $post['name'],
                ];
                $lastId = $model->createNewNode($data);
            }
            elseif ($post['nameOfAction'] == 'delete') {
                $result = $model->deleteNode($post['id']);
            }
            elseif ($post['nameOfAction'] == 'active') {
                $model->changeActive($post['ids'], 1);
                exit();
            }
            elseif ($post['nameOfAction'] == 'deactive') {
                $model->changeActive($post['ids'], 0);
                exit();
            }

            if ($result || $lastId) {
                return $model::createTree($post['openedIds'], $lastId);
            }

        }

        return $this->render('categories-manager', compact([
//            'arrDeactivatedId'
        ]));
    }

    public function actionCitiesManager() {
        return $this->render('cities-manager');
    }

}
