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

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $result = null;
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
            elseif ($post['nameOfAction'] == 'changeActive') {
                $model->changeActive($post['ids'], $post['value']);
                exit();
            }
            elseif ($post['nameOfAction'] == 'rename') {
                $model->renameNode($post['id'], $post['value']);
                exit();
            }
            elseif ($post['nameOfAction'] == 'moveUp') {
                $result = $model->moveUp($post['id'], $post['siblingId']);
            }
            elseif ($post['nameOfAction'] == 'moveDown') {
                $result = $model->moveDown($post['id'], $post['siblingId']);
            }

            if ($result || $lastId) {
                return $model::createTree($post['openedIds'], $lastId);
            }

        }

        return $this->render('categories-manager');
    }

    public function actionCitiesManager() {
        return $this->render('cities-manager');
    }

}
