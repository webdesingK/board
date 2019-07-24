<?php


    namespace backend\models;

    use yii\db\Query;
    use dastanaron\translit\Translit;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\helpers\BaseInflector;
    use yii\db\Transaction;

    class Filters {

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

                if (!empty((new Query())
                    ->select('rus')
                    ->from('filters.filtersData')
                    ->where('rus = :rus')
                    ->addParams([':rus' => $rusTableName])
                    ->all())) {
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
                    ->batchInsert('filters.' . $latTableName, ['title'], $titles)// $titles - не привязанные параметры (чистые данные) Уязвимое место !!! (Возможно ошибаюсь, нет опыта по batchInsert)
                    ->execute();

                $db
                    ->createCommand()
                    ->insert('filters.filtersData', [
                        'rus' => $rusTableName,
                        'lat' => $latTableName,
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
                    'text' => $e->getMessage()
//                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        static public function getNames(int $idParentCategory) {

            try {
                $names = (new Query())
                    ->select('rus')
                    ->from('filtersData')
                    ->where('idParentCategory = :id')
                    ->addParams([':id' => $idParentCategory])
                    ->all(Yii::$app->get('filtersDb'));
            }
            catch (\Throwable $e) {

            }
            return ArrayHelper::getColumn($names, 'rus');
        }

        static public function getTitles($name) {
            $filtersDb = Yii::$app->get('filtersDb');
            $tableName = self::getFilterName($name, 'lat');
            try {
                if ($tableName) {
                    $titles = (new Query())
                        ->select('title')
                        ->from($tableName)
                        ->all($filtersDb);
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
            $filtersDb = Yii::$app->get('filtersDb');
            $tableName = self::getFilterName($data['name'], 'lat');
            try {

                if ($tableName && !empty($data['arrList'])) {

                    foreach ($data['arrList'] as $datum) {
                        $titles[] = [$datum];
                    }

                    $filtersDb
                        ->createCommand()
                        ->truncateTable($tableName)
                        ->execute();
                    $filtersDb
                        ->createCommand()
                        ->batchInsert($tableName, ['title'], $titles)// $titles - чистые данные Уязвимое место !!! (Возможно ошибаюсь, нет опыта по batchInsert)
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
//                    'text' => 'Ошибка базы данных'
                    'text' => $e->getMessage()
                ];
            }

        }

        private function getFilterName($name, $type) {
            try {
                if ($type == 'lat') {
                    $tableName = (new Query())
                        ->select('lat')
                        ->from('filtersData')
                        ->where('rus = :rus')
                        ->addParams([':rus' => $name])
                        ->one(Yii::$app->get('filtersDb'));
                    return $tableName['lat'];
                }
                if ($type == 'rus') {
                    $tableName = (new Query())
                        ->select('rus')
                        ->from('filtersData')
                        ->where('lat = :lat')
                        ->addParams([':rus' => $name])
                        ->one(Yii::$app->get('filtersDb'));
                    return $tableName['rus'];
                }
            }
            catch (\Throwable $e) {

            }
        }

        static public function delete(array $data) {
            $tableName = self::getFilterName($data['nameFilter'], 'lat');
            $filtersDb = Yii::$app->get('filtersDb');
            try {

                $idFilter = (new Query())
                    ->select('id')
                    ->from('filtersData')
                    ->where(['lat' => $tableName])
                    ->one($filtersDb);

                $bondedCategories = (new Query())
                    ->select('idCategory')
                    ->from('bondedFiltersIds')
                    ->where(['idFilter' => $idFilter['id']])
                    ->all($filtersDb);

                $bondedCategories = ArrayHelper::getColumn($bondedCategories, 'idCategory');

                if (!empty($bondedCategories)) {
                    $bondedCategories = (new Query())
                        ->select(['id', 'name'])
                        ->from('board.categories')
                        ->where(['id' => $bondedCategories])
                        ->all($filtersDb);

                    if (count($bondedCategories) > 1) {
                        $responseStr = "Нельзя удалить привязанный фильтр. Сначала отвяжи его от следующих категорий:\n";
                    }
                    else {
                        $responseStr = "Нельзя удалить привязанный фильтр. Сначала отвяжи его от следующей категории:\n";
                    }

                    foreach ($bondedCategories as $category) {
                        $parents = Categories::getAllParents($category['id']);
                        $responseStr .= $parents[0]['name'] . " - " . $parents[1]['name'] . " - " . $category['name'] . "\n";
                    }

                    return [
                        'status' => false,
                        'text' => $responseStr
                    ];
                }

                $filtersDb
                    ->createCommand()
                    ->dropTable($tableName)
                    ->execute();

                $filtersDb
                    ->createCommand()
                    ->delete('filtersData', ['lat' => $tableName])
                    ->execute();
                $countRow = (new Query())
                    ->from('filtersData')
                    ->count('*', $filtersDb);

                if ($countRow == 0) {
                    $filtersDb
                        ->createCommand()
                        ->truncateTable('filtersData')
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
//                    'text' => 'Ошибка базы данных'
                    'text' => $e->getMessage()
                ];
            }
        }

        static public function getBondedFilters($data) {
            $filtersDb = Yii::$app->get('filtersDb');
            try {
                $bondedFilters = (new Query())
                    ->select('filter')
                    ->from('bondedFiltersData')
                    ->where('idCategory = :idCategory')
                    ->addParams([':idCategory' => $data['idCategory']])
                    ->one($filtersDb);
                if ($bondedFilters) {
                    $bondedFilters = unserialize($bondedFilters['filter']);
                    foreach ($bondedFilters as $key => $value) {
                        $rusName = (new Query())
                            ->select('rus')
                            ->from('filtersData')
                            ->where('lat = :lat')
                            ->addParams([':lat' => $value])
                            ->one($filtersDb);
                        $urlsAndRusNames[$key] = $rusName['rus'];
                    }
                }
                else {
                    $urlsAndRusNames = [];
                }
                return [
                    'status' => true,
                    'arrOption' => self::getNames($data['idParentCategory']),
                    'filters' => $urlsAndRusNames
                ];
            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
//                    'text' => 'Ошибка базы данных'
                    'text' => $e->getMessage()
                ];
            }

        }

        static public function saveBondedFilters(array $data) {

            $filtersDb = Yii::$app->get('filtersDb');

            try {

                if (empty($data['bondedFilters'])) {
                    $filtersDb
                        ->createCommand()
                        ->delete('bondedFiltersIds', 'idCategory = :id')
                        ->bindValue(':id', $data['idCategory'])
                        ->execute();
                    $filtersDb
                        ->createCommand()
                        ->delete('bondedFiltersData', 'idCategory = :id')
                        ->bindValue('id', $data['idCategory'])
                        ->execute();
                    $category = Categories::getCategoryById($data['idCategory']);
                    $parents = Categories::getAllParents($category->id);

                    $countRow = (new Query())
                        ->from('bondedFiltersData')
                        ->count('*', $filtersDb);

                    if ($countRow == 0) {
                        $filtersDb
                            ->createCommand()
                            ->truncateTable('bondedFiltersData')
                            ->execute();
                    }

                    $countRow = (new Query())
                        ->from('bondedFiltersIds')
                        ->count('*', $filtersDb);

                    if ($countRow == 0) {
                        $filtersDb
                            ->createCommand()
                            ->truncateTable('bondedFiltersIds')
                            ->execute();
                    }

                    return [
                        'status' => true,
                        'text' => 'Все фильтры отвязаны от категории: ' . $parents[0]['name'] . ' - ' . $parents[1]['name'] . ' - ' . $category->name
                    ];
                }

                $filterRusNames = array_values($data['bondedFilters']);

                foreach ($data['bondedFilters'] as $key => $value) {
                    $latName = (new Query())
                        ->select('lat')
                        ->from('filtersData')
                        ->where('rus = :rus')
                        ->addParams([':rus' => $value])
                        ->one($filtersDb);
                    $urlsAndLatNames[$key] = $latName['lat'];
                }

                $filtersIds = (new Query())
                    ->select('id')
                    ->from('filtersData')
                    ->where(['rus' => $filterRusNames])
                    ->all($filtersDb);

                $filtersDb
                    ->createcommand()
                    ->delete('bondedFiltersIds', 'idCategory = :idCategory')
                    ->bindValue(':idCategory', $data['idCategory'])
                    ->execute();

                foreach ($filtersIds as $filtersId) {
                    $ids[] = [
                        (int)$data['idCategory'],
                        (int)$filtersId['id']
                    ];
                }

                $filtersDb
                    ->createCommand()
                    ->batchInsert('bondedFiltersIds', ['idCategory', 'idFilter'], $ids)
                    ->execute();

                $urlsAndLatNames = serialize($urlsAndLatNames);

                $countRow = (new Query())
                    ->from('bondedFiltersData')
                    ->where('idCategory = :idCategory')
                    ->addParams([':idCategory' => $data['idCategory']])
                    ->count('*', $filtersDb);
                if ($countRow > 0) {
                    $filtersDb
                        ->createCommand()
                        ->update('bondedFiltersData', ['filter' => $urlsAndLatNames], 'idCategory = ' . $data['idCategory'])
                        ->execute();
                    return [
                        'status' => true,
                        'text' => 'Обновление фильтра прошло успешно'
                    ];
                }
                else {
                    $filtersDb
                        ->createCommand()
                        ->insert('bondedFiltersData', [
                            'idCategory' => $data['idCategory'],
                            'filter' => $urlsAndLatNames
                        ])
                        ->execute();
                    return [
                        'status' => true,
                        'text' => 'Привязка фильтра прошла успешно'
                    ];
                }

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => $e->getMessage()
                ];
            }
        }

    }