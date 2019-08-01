<?php


    namespace adminPanel\models\filters;

    use yii\db\ActiveRecord;

    class Filters extends ActiveRecord {

        static public function tableName() {
            return 'filters.filters';
        }

        /**
         * @param $column string
         * @param $condition string
         * @return array|ActiveRecord|null
         */

        public function getOneData($column, $condition) {

            try {
                return self::find()->select($column)->where($condition . ' = :cond')->addParams([':cond' => $condition])->one();
            }
            catch (\Throwable $e) {

            }

        }

    }