<?php

namespace backend\controllers;

use common\models\Categories;
use yii\web\Controller;
use Yii;

class AdminController extends Controller {

    public function actionIndex() {

        $model = new Categories();

        return $this->render('index', compact('model'));
    }

    public function actionTreeManager() {

        $model = new Categories();

        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $parentId = $post['parentId'];
            $name = $post['name'];
            $parent = $model::find()->andWhere(['id' => $parentId])->one();

            if ($model->load(Yii::$app->request->post(), '') && $model->prependTo($parent) && $model->save()) {
                $result = $model::find()->andWhere(['name' => $name])->one();
                return $this->renderPartial('categories-blocks', compact(
                    'result'
                ));
            }
            else {
                return 'error';
            }
        }

        return $this->render('tree-manager', compact('model'));
    }

//    public function actionRoot() {
//        $model = new Categories(['name' => 'Категории']);
//        $model->makeRoot();
//
//    }

}
