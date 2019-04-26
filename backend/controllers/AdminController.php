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
        $arrDeactivatedId = $model->getDeactivateId();

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = $post['id'];
            $result = null;
            $arrOpenedId = null;
            $arrDeactivatedId = null;
            $lastId = null;
            if (isset($post['arrId'])) $arrOpenedId = $post['arrId'];
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
            if ($post['nameOfAction'] == 'active') {
                $model->changeActivate($id, $post['active']);
                exit();
            }

            if ($result) {
                return $model::createTree($arrOpenedId, $arrDeactivatedId, $lastId);
            }

        }

        return $this->render('categories-manager', compact([
            'arrDeactivatedId'
        ]));
    }

    public function actionCitiesManager() {
        return $this->render('cities-manager');
    }

}
