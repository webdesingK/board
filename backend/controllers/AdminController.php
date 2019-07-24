<?php

    namespace backend\controllers;

    use backend\models\Categories;
    use backend\models\Cities;
    use backend\models\Filters;
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
                $request = Json::decode(file_get_contents('php://input'));
                $result = Filters::create($request);
                return json_encode($result);
            }

            return $this->render('create-filters');
        }

        public function actionEditFilters() {
             if (Yii::$app->request->isAjax) {
                $request = Json::decode(file_get_contents('php://input'));

                    if ($request['requestId'] == 'getFilterTitles') {
                        $result = [
                            'status' => true,
                            'text' => '
        <tr>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">${count}</span>
                    <input value="sdsj" type="text" class="form-control">
                    <span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
                </div>
            </td>
        </tr>
        <tr>
          <td>
                <div class="input-group">
                    <span class="input-group-addon">${count}</span>
                    <input value="sdsj" type="text" class="form-control">
                    <span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
                </div>
          </td>
        </tr>'
                        ];
                        return json_encode($result);
                }

                if ($request['requestId'] == 'editFilter') {
                    $arr = [
                        'status' => true,
                        'text' => 'все хорошо'
                    ];
                    return json_encode($arr);
                }
                if ($request['requestId'] == 'deleteFilter') {
                    $arr = [
                        'status' => true,
                        'text' => 'все хорошо'
                    ];
                    return json_encode($arr);
                }

            }
            return $this->render('edit-filters');
        }

        public function actionBindFilters() {

            if (Yii::$app->request->isAjax) {

                $request = Json::decode(file_get_contents('php://input'));

                if ($request['requestId'] == 'getAllFilterNames') {
                    $arr = [
                        'vasa',
                        'petya',
                        'ghora',
                        'masha',
                        'pasha',
                        'dasha',
                        'kasha'
                    ];
                    return json_encode($arr);
                }
                if ($request['requestId'] == 'saveBondedFilters') {
                    $arr = [
                        'status' => true,
                        'text' => 'все хорошо'
                    ];
                    return json_encode($arr);
                }
                if ($request['requestId'] == 'getCategoriesLvl2') {
                    $arr = [
                        'status' => true,
                        'text' => '<option value="2">трусы</option>
            <option value="3">одежда</option>
            <option value="4">одежда</option>
            <option value="5">одежда</option>'
                    ];
                    return json_encode($arr);
                }
                if ($request['requestId'] == 'getCategoriesLvl3') {
                    $arr = [
                        'status' => true,
                        'text' => '<option value="2">трусы</option>
            <option value="3">одежда</option>
            <option value="4">одежда</option>
            <option value="5">одежда</option>'
                    ];
                    return json_encode($arr);
                }
                if ($request['requestId'] == 'getBondedFilters') {
                    $arr = [
                        'status' => true,
                        'namesOptions' => [
                        'vasa',
                        'petya',
                        'ghora',
                        'masha',
                        'pasha',
                        'dasha',
                        'kasha'
                    ],
                        'idsOptions' => [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6',
                        '7'
                    ],
                        'text' => '
                <div class="input-group">
                    <span class="input-group-addon counts">1</span>
                    <select class="form-control" id="select-filters-js">
                        <option value="1">1</option>
                        <option value="2" selected="selected">размеры</option>
                        <option value="3">2</option>
                    </select>
                    <span class="input-group-addon" title="Удалить пункт">
                        <i class="glyphicon glyphicon-remove-circle text-danger"></i>
                    </span>
                </div>'
                    ];
                    return json_encode($arr);
                }
            }

            return $this->render('bind-filters');
        }

    }
