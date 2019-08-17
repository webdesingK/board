<?php


    namespace adminPanel\models\filtersManager;

    use dastanaron\translit\Translit;
    use phpDocumentor\Reflection\Types\Self_;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\db\Exception;
    use yii\db\Query;
    use yii\helpers\BaseInflector;

    class FiltersManager {

        public function create($data) {

            if (!empty($data)) {

                $titles = [];

                foreach ($data['arrList'] as $datum) {
                    $titles[] = [$datum];
                }

                $translator = new Translit();
                $rusTableName = trim($data['name']);
                $latTableName = $translator->translit($rusTableName, false, 'ru-en');
                $latTableName = BaseInflector::variablize($latTableName);

                $filters = new Filters();

                $db = Yii::$app->db;

                try {

                    try{
                        $filterExists = $filters->findAllByRusName($rusTableName);
                    }
                    catch (\Throwable $e) {
                        return static::success($e->getMessage());
                    }


                    if (!empty($filterExists)) {
                        return static::error('Такой фильтр уже существует');
                    }

                    $filters->rusName = $rusTableName;
                    $filters->latName = $latTableName;
                    $filters->url = $data['url'];
                    $filters->idParentCategory = $data['idCategory'];

                    if ($filters->insert()) {

                        $db->createCommand()
                            ->createTable('filters.' . $latTableName, [
                                'id' => 'pk',
                                'title' => 'string'
                            ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')
                            ->execute();

                        $db->createCommand()
                            ->batchInsert('filters.' . $latTableName, ['title'], $titles)
                            ->execute();

                        return static::success("Фильтр '$rusTableName' успешно создан");

                    }
                    else {
                        return static::error('Не верные данные');
                    }

                }
                catch (\Throwable $e) {
                    return static::error();
                }
            }
            else {
                return static::error('Нет данных');
            }
        }

        /**
         * @param $data array
         * @return array|mixed
         */

        public function edit($data) {
            $db = Yii::$app->db;
            $filters = new Filters();

            try {
                $tableName = $filters->getLatNameByRusName($data['name']);
                if (!empty($data['arrList'])) {

                    $titles = [];

                    foreach ($data['arrList'] as $datum) {
                        $titles[] = [$datum];
                    }

                    $db->createCommand()
                        ->truncateTable('filters.' . $tableName)
                        ->execute();

                    $db->createCommand()
                        ->batchInsert('filters.' . $tableName, ['title'], $titles)
                        ->execute();

                    return static::success('Фильтр \'' . $data['name'] . '\' успешно обновлен');

                }
                else {
                    return static::error('Нет данных');
                }
            }
            catch (\Throwable $e) {
                return static::error();
            }
        }

        /**
         * @param $filterName string
         * @return array
         */

        public function getTitles($filterName) {

            $filters = new Filters();

            try {
                $tableName = $filters->getLatNameByRusName($filterName);
                $titles = (new Query())
                    ->select('title')
                    ->from('filters.' . $tableName)
                    ->all();

                return [
                    'status' => true,
                    'titles' => $titles
                ];
            }
            catch (\InvalidConfigException $e) {
                return static::error();
            }

        }

        /**
         * @param $text string
         * @return array
         */

        private function success($text) {
            return [
                'status' => true,
                'text' => $text
            ];
        }

        /**
         * @param null $text
         * @return mixed
         */

        private function error($text = null) {

            $result['status'] = false;

            if ($text) {
                $result['text'] = $text;
            }
            else {
                $result['text'] = Yii::$app->params['serverErrorMessage'];
            }

            return $result;
        }

    }