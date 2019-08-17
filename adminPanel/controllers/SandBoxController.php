<?php


    namespace adminPanel\controllers;

    use adminPanel\models\filtersManager\Categories;
    use adminPanel\models\filtersManager\Filters;
    use yii\web\Controller;
    use Yii;

    class SandBoxController extends Controller {

        public function actionIndex() {

            $session = Yii::$app->session;

            $filters = new Filters();
            $categories = new Categories();

            if (Yii::$app->request->isAjax) {
                $request = Yii::$app->request->post();
                if ($request['request'] == 'add') {
                    return $this->renderPartial('input', [
                        'filters' => $filters,
                        'session' => $session
                    ]);
                }
            }

            if ($session->has('idTitle')) {
                $session->remove('idTitle');
            }

            return $this->render('index', [
                'filters' => $filters,
                'categories' => $categories,
            ]);
        }

    }