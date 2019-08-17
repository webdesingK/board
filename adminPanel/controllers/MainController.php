<?php

    namespace adminPanel\controllers;

    use yii\web\Controller;

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

            return $this->render('index', [

            ]);
        }
    }

