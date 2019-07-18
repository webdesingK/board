<?php


    namespace backend\models;

    use yii\db\Query;
    use dastanaron\translit\Translit;
    use Yii;
    use yii\helpers\BaseInflector;

    class Filters {

        static public function create(array $data) {

            $translator = new Translit();

            $rusTableName = $data['name'];
            $titles = [];

            $latinTableName = $translator->translit($rusTableName, false, 'ru-en');
            $latinTableName = BaseInflector::variablize($latinTableName);

            foreach ($data['arrList'] as $datum) {
                $titles[] = [$datum];
            }

            $db = new Query();
            $filtersDb = Yii::$app->get('filtersDb');

            try {

                if ($db->select('rus')->from('translatedNames')->where(['rus' => $rusTableName])->all($filtersDb)) {
                    return [
                        'status' => false,
                        'text' => 'Такой фильтр уже существует'
                    ];
                }

                $db->createCommand($filtersDb)->createTable($latinTableName, [
                    'id' => 'pk',
                    'title' => 'string'
                ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')->execute();

                $db->createCommand($filtersDb)->batchInsert($latinTableName, ['title'], $titles)->execute();

                $db->createCommand($filtersDb)->insert('translatedNames', [
                    'rus' => $rusTableName,
                    'lat' => $latinTableName
                ])->execute();

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

            $db = new Query();
            $filtersDb = Yii::$app->filtersDb;

            try {
              $names = $db->select(['rus', 'lat'])->from('translatedNames')->all($filtersDb);
              return $names;
            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }
        }



    }