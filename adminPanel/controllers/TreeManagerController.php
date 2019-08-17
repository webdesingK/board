<?php


    namespace adminPanel\controllers;

    use adminPanel\models\treeManagers\Categories;
    use adminPanel\models\treeManagers\Cities;
    use Yii;
    use yii\web\Controller;

    class TreeManagerController extends Controller {

        /**
         * @return string
         * @throws \yii\web\ServerErrorHttpException
         */

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

            return $this->render('categories-manager', [
                'model' => $model
            ]);
        }

        /**
         * @return string
         * @throws \yii\web\ServerErrorHttpException
         */

        public function actionCitiesManager() {

            $model = new Cities();

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
    }