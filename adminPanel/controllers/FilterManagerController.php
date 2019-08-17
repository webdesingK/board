<?php

    namespace adminPanel\controllers;

    use adminPanel\models\filtersManager\Filters;
    use adminPanel\models\filtersManager\FiltersManager;
    use Yii;
    use yii\helpers\Json;
    use yii\web\Controller;
    use adminPanel\models\filtersManager\Categories;
    use backend\models\categoriesFilters\CategoriesSearch;
    use adminPanel\models\filters\FiltersCategories;

    class FilterManagerController extends Controller {

        public function actionCreateFilters() {

            $categories = new Categories();
            $managerFilters = new FiltersManager();

            if (Yii::$app->request->isAjax) {
                $request = Json::decode(file_get_contents('php://input'));
                $result = $managerFilters->create($request);
                return Json::encode($result);
            }

            return $this->render('create-filters', [
                'categories' => $categories
            ]);
        }

        public function actionEditFilters() {

            $filtersManager = new FiltersManager();
            $filters = new Filters();

            if (Yii::$app->request->isAjax) {

                $request = Json::decode(file_get_contents('php://input'));

                if ($request['requestId'] == 'getFilterTitles') {

                    $result = $filtersManager->getTitles($request['nameFilter']);

                    if ($result['status']) {
                        $titlesHtml = $this->renderPartial('filter-titles', [
                            'titles' => $result['titles']
                        ]);
                        $result['text'] = $titlesHtml;
                        unset($result['titles']);
                    }

                    return Json::encode($result);
                }

                if ($request['requestId'] == 'editFilter') {
                    $result = $filtersManager->edit($request);
                    return Json::encode($result);
                }
                if ($request['requestId'] == 'deleteFilter') {
                    $result = $filters->delete($request);
                    return Json::encode($result);
                }

            }

            return $this->render('edit-filters', [
                'filters' => $filters
            ]);
        }

        public function actionBindFilters() {

            $categories = new Categories();
//            $filters = new ManagerFilters();

            if (Yii::$app->request->isAjax) {

                $request = Json::decode(file_get_contents('php://input'));

                if ($request['requestId'] == 'getCategoriesLvl2' || $request['requestId'] == 'getCategoriesLvl3') {

                    $result = $categories->getChildrenById($request['idCategory']);

                    if ($result['status']) {
                        $childrenHtml = $this->renderPartial('children-categories', [
                            'children' => $result['children']
                        ]);
                        $result['text'] = $childrenHtml;
                        unset($result['children']);
                    }

                    return Json::encode($result);
                }
                if ($request['requestId'] == 'getBondedFilters') {
                    $result = $filters->getBondedFilters($request);

                    if ($result['status']) {
                        $bondedFiltersHtml = $this->renderPartial('bonded-filters', [
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
                    $result = $filters->saveBondedFilters($request);
                    return Json::encode($result);
                }
            }

            return $this->render('bind-filters', [
                'categories' => $categories
            ]);
        }

        public function actionViewCategoryFilters() {

            $searchModel = new CategoriesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $filtersCategories = new FiltersCategories();
            $filters = new ManagerFilters();

            return $this->render('category-filters', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'filtersCategories' => $filtersCategories
            ]);
        }

        public function actionFilterCategories() {
            return $this->render('filters-categories');
        }

        public function actionViewFilterCategories() {

        }

    }