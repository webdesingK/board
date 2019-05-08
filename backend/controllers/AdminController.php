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
            switch ($post['nameOfAction']) {
                case 'create':
                    $lastId = $model->createNode($post['id'], $post['name']);
                    break;
                case 'rename':
                    return $model->renameNode($post['id'], $post['newName']) ? 'ok' : 'error';
                    break;
                case 'changeActive':
                    return $model->changeActive($post['ids'], $post['value']) ? 'ok' : 'error';
                    break;
                case 'move':
                    $result = $model->moveNode($post['id'], $post['siblingId'], $post['direction']);
                    break;
                case 'delete':
                    $result = $model->deleteNode($post['id']);
                    break;
            }

            if ($lastId || $result) {
                return $model::createTree($post['openedIds'], $lastId);
            }
            else {
                return 'error';
            }

        }

        return $this->render('categories-manager');
}

    public function actionCitiesManager() {
        return $this->render('cities-manager');
    }

}
