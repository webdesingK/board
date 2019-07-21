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

            $rusTableName = $data['name'];

            $latinTableName = $translator->translit($rusTableName, false, 'ru-en');
            $latinTableName = BaseInflector::variablize($latinTableName);

            foreach ($data['arrList'] as $datum) {
                $titles[] = [$datum];
            }

            $qb = new Query();
            $filtersDb = Yii::$app->get('filtersDb');

            try {

                if (!empty($qb
                    ->select('rus')
                    ->from('translatedNames')
                    ->where('rus = :rus')
                    ->addParams([':rus' => $rusTableName])
                    ->all($filtersDb))) {
                    return [
                        'status' => false,
                        'text' => 'Такой фильтр уже существует'
                    ];
                }

                $filtersDb
                    ->createCommand()
                    ->createTable($latinTableName, [
                        'id' => 'pk',
                        'title' => 'string'
                    ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')
                    ->execute();

                $filtersDb
                    ->createCommand()
                    ->batchInsert($latinTableName, ['title'], $titles)// $titles - не привязанные параметры (чистые данные) Уязвимое место !!! (Возможно ошибаюсь, нет опыта по batchInsert)
                    ->execute();

                $filtersDb
                    ->createCommand()
                    ->insert('translatedNames', [
                        'rus' => $rusTableName,
                        'lat' => $latinTableName
                    ])
                    ->execute();

                return [
                    'status' => true,
                    'text' => 'Фильтр \'' . $rusTableName . '\' успешно создан'
                ];

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        static public function getNames() {

            try {
                $names = (new Query())
                    ->select('rus')
                    ->from('translatedNames')
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
                    'text' => 'Ошибка базы данных'
                ];
            }

        }

        private function getFilterName($name, $type) {
            try {
                if ($type == 'lat') {
                    $tableName = (new Query())
                        ->select('lat')
                        ->from('translatedNames')
                        ->where('rus = :rus')
                        ->addParams([':rus' => $name])
                        ->one(Yii::$app->get('filtersDb'));
                    return $tableName['lat'];
                }
                if ($type == 'rus') {
                    $tableName = (new Query())
                        ->select('rus')
                        ->from('translatedNames')
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
                $filtersDb
                    ->createCommand()
                    ->dropTable($tableName)
                    ->execute();
                $filtersDb
                    ->createCommand()
                    ->delete('translatedNames', ['lat' => $tableName])
                    ->execute();
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

        static public function getBondedFilters($idCategory) {
            $filtersDb = Yii::$app->get('filtersDb');
            try {
                $bondedFilters = (new Query())
                    ->select('filter')
                    ->from('bondedFilters')
                    ->where('idCategory = :idCategory')
                    ->addParams([':idCategory' => $idCategory])
                    ->one($filtersDb);
                if ($bondedFilters) {
                    $bondedFilters = unserialize($bondedFilters['filter']);
                    $filterLatNames = array_values($bondedFilters);
                    $filterUrls = array_keys($bondedFilters);
                    $filterRusNames = (new Query())
                        ->select('rus')
                        ->from('translatedNames')
                        ->where(['lat' => $filterLatNames])
                        ->all($filtersDb);
                    $count = count($filterUrls);
                    for ($i = 0; $i < $count; $i++) {
                        $formattedFilters[$filterUrls[$i]] = $filterRusNames[$i]['rus'];
                    }
                }
                else {
                    $formattedFilters = [];
                }
                return [
                    'status' => true,
                    'formattedFilters' => $formattedFilters
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

            $filterRusNames = array_values($data['bondedFilters']);
            $filterUrls = array_keys($data['bondedFilters']);
            $filtersDb = Yii::$app->get('filtersDb');
            $count = count($data['bondedFilters']);

            try {
                $filterLatNames = (new Query())
                    ->select('lat')
                    ->from('translatedNames')
                    ->where(['rus' => $filterRusNames])
                    ->all($filtersDb);
                for ($i = 0; $i < $count; $i++) {
                    $filters[$filterUrls[$i]] = $filterLatNames[$i]['lat'];
                }
                $filters = serialize($filters);

                $countRow = (new Query())
                    ->from('bondedFilters')
                    ->where('idCategory = :idCategory')
                    ->addParams([':idCategory' => $data['categoryId']])
                    ->count('*', $filtersDb);
                if ($countRow > 0) {
                    $filtersDb
                        ->createCommand()
                        ->update('bondedFilters', ['filter' => $filters], 'idCategory = ' . $data['categoryId'])
                        ->execute();
                    return [
                        'status' => true,
                        'text' => 'Обновление прошло успешно'
                    ];
                }
                else {
                    $filtersDb
                        ->createCommand()
                        ->insert('bondedFilters', [
                            'idCategory' => $data['categoryId'],
                            'filter' => $filters
                        ])
                        ->execute();
                    return [
                        'status' => true,
                        'text' => 'Привязка прошла успешно'
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

    }