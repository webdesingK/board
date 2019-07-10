<?php

    namespace backend\controllers;

    use backend\models\Categories;
    use backend\models\Cities;
    use yii\helpers\Json;
    use yii\web\Controller;
    use Yii;

    class AdminController extends Controller {

        public function actionIndex() {
            return $this->render('index');
        }

        public function actionCategoriesManager() {

            $model = new Categories;

            if (Yii::$app->request->isAjax) {
                $model->postData = Yii::$app->request->post();
                $result = null;

                Yii::$app->cache->delete('categoryMenuAllData');

                switch ($model->postData['nameOfAction']) {
                    case 'create':
                        $result = $model->createNode();
                        break;
                    case 'rename':
                        return $model->renameNode() ? 'ok' : 'error';
                        break;
                    case 'changeActive':
                        return $model->changeActive() ? 'ok' : 'error';
                        break;
                    case 'move':
                        $result = $model->moveNode();
                        break;
                    case 'delete':
                        $result = $model->deleteNode();
                        break;
                }

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

            $model = new Cities;

            if (Yii::$app->request->isAjax) {
                $model->postData = Yii::$app->request->post();
                $result = null;

                Yii::$app->cache->delete('cityMenuAllData');

                switch ($model->postData['nameOfAction']) {
                    case 'create':
                        $result = $model->createNode();
                        break;
                    case 'rename':
                        return $model->renameNode() ? 'ok' : 'error';
                        break;
                    case 'changeActive':
                        return $model->changeActive() ? 'ok' : 'error';
                        break;
                    case 'move':
                        $result = $model->moveNode();
                        break;
                    case 'delete':
                        $result = $model->deleteNode();
                        break;
                }

                if ($result) {
                    return $this->renderPartial('cities-manager', [
                        'model' => $model
                    ]);
                }
                else {
                    return 'error';
                }

            }

            return $this->render('cities-manager', compact('model'));

        }

        public function actionCreateFilters() {

            if (Yii::$app->request->isAjax) {
//                $request = file_get_contents('php://input');
//          $request = json_decode($request);
//                $request = Json::decode($request);
//                return $this->renderPartial('create-filters', compact('request'));
            }

            return $this->render('create-filters');
        }

        public function actionEditFilters() {
            return $this->render('edit-filters');
        }

        public function actionBindFilters() {
            return $this->render('bind-filters');
        }

    }
