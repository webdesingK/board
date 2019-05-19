<?php

namespace backend\controllers;

use backend\models\Categories;
use common\models\cities\Cities;
use yii\web\Controller;
use Yii;

class AdminController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCategoriesManager() {

        $model = new Categories();

        if (Yii::$app->request->isAjax) {
            $model->data = Yii::$app->request->post();
            $result = null;
            switch ($model->data['nameOfAction']) {
                case 'create':
                    $result = $model->createNode();
                    break;
//                case 'rename':
//                    return $model->renameNode($post['id'], $post['newName']) ? 'ok' : 'error';
//                    break;
//                case 'changeActive':
//                    return $model->changeActive($post['ids'], $post['value']) ? 'ok' : 'error';
//                    break;
//                case 'move':
//                    $result = $model->moveNode($post['id'], $post['siblingId'], $post['direction']);
//                    break;
//                case 'delete':
//                    $result = $model->deleteNode($post['id']);
//                    break;
            }
//
            if ($result) {
                return $this->renderPartial('categories-manager', [
                    'model' => $model
                ]);
            }
            else {
                return 'error';
            }

        }

        return $this->render('categories-manager', compact('model'));
}

    public function actionCitiesManager() {

        $model = new Cities();

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
                return $model->createTree($post['openedIds'], $lastId);
            }
            else {
                return 'error';
            }

        }

        return $this->render('cities-manager', compact('model'));

    }

}
