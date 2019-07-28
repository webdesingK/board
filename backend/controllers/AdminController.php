<?php

    namespace backend\controllers;

    use backend\models\Categories;
    use backend\models\Cities;
    use backend\models\categoriesFilters\CategoriesSearch;
    use backend\models\Filters;
    use yii\helpers\Json;
    use yii\web\Controller;
    use Yii;

    class AdminController extends Controller {

        public function actions() {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }

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
                $request = Json::decode(file_get_contents('php://input'));
                $result = Filters::create($request);
                return Json::encode($result);
            }

            return $this->render('create-filters/create-filters');
        }

        public function actionEditFilters() {

            if (Yii::$app->request->isAjax) {

                $request = Json::decode(file_get_contents('php://input'));

                if ($request['requestId'] == 'getFilterTitles') {

                    $result = Filters::getTitles($request['nameFilter']);

                    if ($result['status']) {
                        $titlesHtml = $this->renderPartial('edit-filters/filter-titles', [
                            'titles' => $result['titles']
                        ]);
                        $result['text'] = $titlesHtml;
                        unset($result['titles']);
                    }

                    return Json::encode($result);
                }

                if ($request['requestId'] == 'editFilter') {
                    $result = Filters::edit($request);
                    return Json::encode($result);
                }
                if ($request['requestId'] == 'deleteFilter') {
                    $result = Filters::delete($request);
                    return Json::encode($result);
                }

            }
            return $this->render('edit-filters/edit-filters');
        }

        public function actionBindFilters() {

            if (Yii::$app->request->isAjax) {

                $request = Json::decode(file_get_contents('php://input'));

                if ($request['requestId'] == 'getCategoriesLvl2' || $request['requestId'] == 'getCategoriesLvl3') {

                    $result = Categories::getChildren($request['idCategory']);

                    if ($result['status']) {
                        $childrenHtml = $this->renderPartial('bind-filters/children-categories', [
                            'children' => $result['children']
                        ]);
                        $result['text'] = $childrenHtml;
                        unset($result['children']);
                    }

                    return Json::encode($result);
                }
                if ($request['requestId'] == 'getBondedFilters') {
                    $result = Filters::getBondedFilters($request);

                    if ($result['status']) {
                        $bondedFiltersHtml = $this->renderPartial('bind-filters/bonded-filters', [
                            'allFilters' => $result['allFilters'],
                            'bondedFilters' => $result['bondedFilters']
                        ]);
                        $result['text'] = $bondedFiltersHtml;
                        unset($result['allFilters']);
                        unset($result['bondedFilters']);
                    }

                    return Json::encode($result);
                }
                if ($request['requestId'] == 'saveBondedFilters') {
                    $result = Filters::saveBondedFilters($request);
                    return Json::encode($result);
                }
            }

            return $this->render('bind-filters/bind-filters');
        }

        public function actionCategoriesFilters() {

            $searchModel = new CategoriesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $categories = new Categories();
            $categoriesFilters = new \backend\models\categoriesFilters\Categories();
            $filters = new Filters();

            return $this->render('categories-filters', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'categories' => $categories,
                'categoriesFilters' => $categoriesFilters,
                'filters' => $filters
            ]);
        }

        public function actionFiltersCategories() {
            return $this->render('filters-categories');
        }

    }
