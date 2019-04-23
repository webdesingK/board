<?php

namespace backend\controllers;

use common\models\Categories;
use yii\web\Controller;
use Yii;

class AdminController extends Controller {

    public function actionIndex() {

//        $model = new Categories();
//
//        if ($model->load(Yii::$app->request->post())) {
//
//            $id = Yii::$app->request->post('Categories')['parent_id'];
//            $parent = $model::find()->andWhere(['id' => $id])->one();
//            if ($model->prependTo($parent) && $model->save()) {
//                return $this->refresh();
//            }
//        }
//
//        if (Yii::$app->request->isAjax) {
//            if (Yii::$app->request->post('nameOfOperate') == 'del') {
//                $id = Yii::$app->request->post('parentId');
//                $el = $model->find()->andWhere(['id' => $id])->one();
//                if ($el->deleteWithChildren()) return $this->refresh();
//            }
//        }

        return $this->render('index');
    }

    public function actionTreeManager() {

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
            else {
                return 'db_error';
            }

        }

        return $this->render('tree-manager');
    }

}
