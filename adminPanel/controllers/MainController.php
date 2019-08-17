<?php

    namespace adminPanel\controllers;

    use Yii;
    use adminPanel\models\UploadFiles;
    use yii\web\Controller;
    use yii\web\UploadedFile;

    class MainController extends Controller {

        /**
         * @return array
         */

        public function actions() {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }

        /**
         * @return mixed
         */

        public function actionIndex() {

//            $model = new UploadFiles();

            if (Yii::$app->request->isPost) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                if ($model->upload()) {
                    $this->goHome();
                }
            }

            return $this->render('index', [
//                'model' => $model
            ]);
        }
    }

