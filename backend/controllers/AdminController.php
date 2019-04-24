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
            $id = $post['id'];
            $result = null;
            $arrId = null;
            $lastId = null;
            if (isset($post['arrId'])) $arrId = $post['arrId'];
            if ($post['nameOfAction'] == 'create') {
                $parent = $model::find()->andWhere(['id' => $id])->one();
                $data = [
                    'parent_id' => $id,
                    'name' => $post['name']
                ];
                $result = ($model->load($data, '') && $model->prependTo($parent));
                $lastId = $model->getLastId($post['name']);

            }
            if ($post['nameOfAction'] == 'delete') {
                $elem = $model::find()->andWhere(['id' => $id])->one();
                $result = $elem->deleteWithChildren();
            }

            if ($result) {
                return $model::createTree($arrId, $lastId);
            }

        }

        return $this->render('categories-manager');
    }

    public function actionCitiesManager() {
        return $this->render('cities-manager');
    }

}
