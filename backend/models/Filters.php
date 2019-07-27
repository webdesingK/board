<?php


    namespace backend\models;

    use yii\base\BaseObject;
    use yii\db\Query;
    use dastanaron\translit\Translit;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\helpers\BaseInflector;
    use yii\db\Transaction;
    use yii\web\ServerErrorHttpException;

    class Filters extends BaseObject {

        static public function create(array $data) {

            $translator = new Translit();

            $rusTableName = trim($data['name']);

            $latTableName = $translator->translit($rusTableName, false, 'ru-en');
            $latTableName = BaseInflector::variablize($latTableName);

            foreach ($data['arrList'] as $datum) {
                $titles[] = [(string)$datum];
            }

            try {

                $db = Yii::$app->db;

                if ((new Query())
                    ->select('rusName')
                    ->from('filters.filters')
                    ->where('rusName = :rusName')
                    ->addParams([':rusName' => $rusTableName])
                    ->one()) {
                    return [
                        'status' => false,
                        'text' => 'Такой фильтр уже существует'
                    ];
                }

                $db
                    ->createCommand()
                    ->createTable('filters.' . $latTableName, [
                        'id' => 'pk',
                        'title' => 'string'
                    ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')
                    ->execute();

                $db
                    ->createCommand()
                    ->batchInsert('filters.' . $latTableName, ['title'], $titles)
                    ->execute();

                $db
                    ->createCommand()
                    ->insert('filters.filters', [
                        'rusName' => $rusTableName,
                        'latName' => $latTableName,
                        'url' => $data['url'],
                        'idParentCategory' => $data['idCategory'],
                    ])
                    ->execute();

                return [
                    'status' => true,
                    'text' => "Фильтр '$rusTableName' успешно создан"
                ];

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        static public function getFilterData($column, $idParentCategory = null) {

            try {
                if ($idParentCategory) {
                    $data = (new Query())
                        ->select($column)
                        ->from('filters.filters')
                        ->where('idParentCategory = :id')
                        ->addParams([':id' => $idParentCategory])
                        ->all();
                }
                else {
                    $data = (new Query())
                        ->select($column)
                        ->from('filters.filters')
                        ->all();
                }
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException($e->getMessage());
            }
            if (is_array($column)) {
                $result = $data;
            }
            else {
                $result = ArrayHelper::getColumn($data, $column);
            }
            return $result;
        }

        static public function getTitles($name) {

            $tableName = self::getFilterName($name, 'latName');

            try {
                if ($tableName) {
                    $titles = (new Query())
                        ->select('title')
                        ->from('filters.' . $tableName)
                        ->all();
                }
                return [
                    'status' => true,
                    'titles' => $titles
                ];
            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }
        }

        static public function edit(array $data) {

            $db = Yii::$app->db;
            $tableName = self::getFilterName($data['name'], 'latName');

            try {

                if ($tableName && !empty($data['arrList'])) {

                    foreach ($data['arrList'] as $datum) {
                        $titles[] = [$datum];
                    }

                    $db
                        ->createCommand()
                        ->truncateTable('filters.' . $tableName)
                        ->execute();
                    $db
                        ->createCommand()
                        ->batchInsert('filters.' . $tableName, ['title'], $titles)
                        ->execute();
                    return [
                        'status' => true,
                        'text' => 'Фильтр \'' . $data['name'] . '\' успешно обновлен'
                    ];
                }

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        private function getFilterName($in, $out) {
            try {
                if ($out == 'latName') {
                    $tableName = (new Query())
                        ->select($out)
                        ->from('filters.filters')
                        ->where('rusName = :rusName')
                        ->addParams([':rusName' => $in])
                        ->one();
                }
                if ($out == 'rusName') {
                    $tableName = (new Query())
                        ->select($out)
                        ->from('filters.filters')
                        ->where('latName = :latName')
                        ->addParams([':latName' => $in])
                        ->one();
                }
                return $tableName[$out];
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        static public function delete(array $data) {

            try {

                $filterData = (new Query())
                    ->select(['id', 'latName'])
                    ->from('filters.filters')
                    ->where('rusName = :rusName')
                    ->addParams([':rusName' => $data['nameFilter']])
                    ->one();

                $bondedCategories = (new Query())
                    ->select('idCategory')
                    ->from('filters.filtersCategories')
                    ->where(['idFilter' => $filterData['id']])
                    ->all();


                if (!empty($bondedCategories)) {

                    $idsBondedCategories = ArrayHelper::getColumn($bondedCategories, 'idCategory');

                    $bondedCategoriesData = (new Query())
                        ->select(['id', 'name'])
                        ->from('board.categories')
                        ->where(['id' => $idsBondedCategories])
                        ->all();

                    if (count($bondedCategories) > 1) {
                        $responseStr = "Нельзя удалить привязанный фильтр. Сначала отвяжи его от следующих категорий:\n";
                    }
                    else {
                        $responseStr = "Нельзя удалить привязанный фильтр. Сначала отвяжи его от следующей категории:\n";
                    }

                    foreach ($bondedCategoriesData as $category) {
                        $parents = Categories::getAllParents($category['id']);
                        $responseStr .= $parents[0]['name'] . " / " . $parents[1]['name'] . " / " . $category['name'] . "\n";
                    }

                    return [
                        'status' => false,
                        'text' => $responseStr
                    ];
                }

                $db = Yii::$app->db;
                $db
                    ->createCommand()
                    ->dropTable('filters.' . $filterData['latName'])
                    ->execute();
                $db
                    ->createCommand()
                    ->delete('filters.filters', ['latName' => $filterData['latName']])
                    ->execute();
                $countRow = (new Query())
                    ->from('filters.filters')
                    ->count('*');
                if ($countRow == 0) {
                    $db
                        ->createCommand()
                        ->truncateTable('filters.' . $filterData['latName'])
                        ->execute();
                }

                return [
                    'status' => true,
                    'text' => 'Фильтр \'' . $data['nameFilter'] . '\' успешно удален'
                ];

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }
        }

        static public function getBondedFilters($data) {

            try {

                $bondedFiltersIds = (new Query())
                    ->select('idFilter')
                    ->from('filters.filtersCategories')
                    ->where('idCategory = :id')
                    ->addParams([':id' => $data['idCategory']])
                    ->all();

                $bondedFiltersIds = ArrayHelper::getColumn($bondedFiltersIds, 'idFilter');

                if ($bondedFiltersIds) {
                    $bondedFilters = (new Query())
                        ->select(['id', 'rusName'])
                        ->from('filters.filters')
                        ->where(['id' => $bondedFiltersIds])
                        ->all();
                }
                else {
                    $bondedFilters = [];
                }

                $allFilters = self::getFilterData(['id', 'rusName'], $data['idParentCategory']);
                $ids = ArrayHelper::getColumn($allFilters, 'id');
                $names = ArrayHelper::getColumn($allFilters, 'rusName');

                return [
                    'status' => true,
                    'namesOptions' => $names,
                    'idsOptions' => $ids,
                    'allFilters' => $allFilters,
                    'bondedFilters' => $bondedFilters
                ];
            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        static public function saveBondedFilters(array $data) {

            $db = Yii::$app->db;

            foreach ($data['idsFilters'] as $value) {
                $ids[] = [
                    $value,
                    $data['idCategory']
                ];
            }

            try {

                $db
                    ->createCommand()
                    ->delete('filters.filtersCategories', 'idCategory = :id')
                    ->bindValue(':id', $data['idCategory'])
                    ->execute();

                if (isset($ids)) {
                    $db
                        ->createCommand()
                        ->batchInsert('filters.filtersCategories', ['idFilter', 'idCategory'], $ids)
                        ->execute();

                    $str = 'Привязка прошла успешно';
                }
                else {
                    $str = 'Отвязка прошла успешно';
                }

                return [
                    'status' => true,
                    'text' => $str
                ];

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }
        }

        // ------------- методы для views Просмотра данных о категориях ----------//

        public function getFiltersIdsBycategoryId($id) {
            try {
                return (new Query())
                    ->select('idFilter')
                    ->from('filters.filtersCategories')
                    ->where(['idCategory' => $id])
                    ->all();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        public function getFiltersDataByIds($ids) {
            try {
                return (new Query())
                    ->select(['rusName', 'url'])
                    ->from('filters.filters')
                    ->where(['id' => $ids])
                    ->all();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        public function getFiltersCategoriesIds() {
            try {
                return (new Query())
                    ->from('filters.filtersCategories')
                    ->all();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        // ---------------------------------------------------------------------- //

    }