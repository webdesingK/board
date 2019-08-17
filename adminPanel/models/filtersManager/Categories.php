<?php


    namespace adminPanel\models\filtersManager;

    use yii\web\ServerErrorHttpException;

    class Categories extends \common\models\categories\Categories {

        /**
         * @return array|\yii\db\ActiveRecord[]
         */

        public function getFirstLvl() {
            return static::find()->select(['id', 'name'])->where(['depth' => 1])->asArray()->all();
        }

        /**
         * @param $id int
         * @return array
         */

        public function getChildrenById($id) {

            try {

                $parent = static::findOne(['id' => (int)$id]);
                $children = $parent->children(1)->select(['id', 'name'])->asArray()->all();

                return [
                    'status' => true,
                    'children' => $children
                ];
            }
            catch (\Throwable $e) {
                return [
                    'status' => false,
                    'text' => 'Ошибка базы данных'
                ];
            }
        }

        static public function getCategoryById($id) {
            try {
                return self::find()->where('id = :id')->addParams([':id' => $id])->asArray()->one();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        static public function getNameById($id) {
            try {
                return self::find()->select('name')->where('id = :id')->addParams([':id' => $id])->asArray()->one();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        public function getAllData($columns) {
            try {
                return self::find()->select($columns)->asArray()->all();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        static public function test($data) {
            return self::findOne($data);
        }

    }