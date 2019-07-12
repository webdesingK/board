<?php


    namespace backend\models;

    use yii\db\Query;

    class Filters {

        static public function createFilter(array $data) {

            $tableName = $data['name'];
            $titles = [];

            foreach ($data['arrList'] as $datum) {
                $titles[] = [$datum];
            }

            $db = new Query();

            try {

                if ($db->select('table_name')->from('information_schema.tables')->where('table_name = :tableName')->addParams([':tableName' => $tableName])->all()) {
                    return [
                        'status' => false,
                        'text' => 'Такой фильтр уже существует'
                    ];
                }

                $db->createCommand()->createTable($tableName, [
                    'id' => 'pk',
                    'title' => 'string'
                ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')->execute();

                $db->createCommand()->batchInsert($tableName, ['title'], $titles)->execute();

                return [
                    'status' => true,
                    'text' => 'Фильтр ' . $tableName . ' успешно создан'
                ];

            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }

        }


    }