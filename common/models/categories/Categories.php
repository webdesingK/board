<?php

    namespace common\models\categories;

    use yii\db\ActiveRecord;
    use creocoder\nestedsets\NestedSetsBehavior;

    class Categories extends ActiveRecord {

        /**
         * {@inheritdoc}
         */
        public static function tableName() {
            return 'categories';
        }

        public function behaviors() {
            return [
                'tree' => [
                    'class' => NestedSetsBehavior::className(),
                ],
            ];
        }

        public function transactions() {
            return [
                self::SCENARIO_DEFAULT => self::OP_ALL,
            ];
        }

        public static function find() {
            return new CategoriesQuery(get_called_class());
        }
    }
