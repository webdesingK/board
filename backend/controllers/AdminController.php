<?php

namespace backend\controllers;

use common\models\Categories;
use yii\web\Controller;
use Yii;

class AdminController extends Controller {

    public function actionIndex() {

        $model = new Categories();

        if ($model->load(Yii::$app->request->post())) {

            $id = Yii::$app->request->post('Categories')['parent_id'];
            $parent = $model::find()->andWhere(['id' => $id])->one();
            if ($model->prependTo($parent) && $model->save()) {
                return $this->refresh();
            }
        }

        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('nameOfOperate') == 'del') {
                $id = Yii::$app->request->post('parentId');
                $el = $model->find()->andWhere(['id' => $id])->one();
                if ($el->deleteWithChildren()) return $this->refresh();
            }
        }

        return $this->render('index', compact([
            'model',
        ]));
    }

    public function actionTreeManager() {

        $model = new Categories();

        $categories = $model::find()->orderBy('lft ASC')->all();

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $parentId = $post['parentId'];
            $parent = $model::find()->andWhere(['id' => $parentId])->one();

            if ($model->load(Yii::$app->request->post(), '') && $model->prependTo($parent) && $model->save()) {
//                $categories = $model::find()->orderBy('lft ASC')->all();
                return $this->renderPartial('tree-manager', compact(
                    'categories'
                ));
            }
        }

        return $this->render('tree-manager', compact(
            'categories'
        ));
    }

//    public function actionRoot() {
//        $model = new Categories(['name' => 'Категории']);
//        $model->makeRoot();
//
//    }

}
