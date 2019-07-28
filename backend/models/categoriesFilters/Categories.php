<?php

    namespace backend\models\categoriesFilters;

    use backend\models\Filters;
    use yii\helpers\ArrayHelper;
    use yii\web\ServerErrorHttpException;

    /**
     * This is the model class for table "categories".
     *
     * @property string $name
     */
    class Categories extends \yii\db\ActiveRecord {
        /**
         * {@inheritdoc}
         */
        public static function tableName() {
            return 'categories';
        }

        /**
         * {@inheritdoc}
         */
        public function attributeLabels() {
            return [
                'name' => 'Название категории',
            ];
        }

        /**
         * @return array
         * @throws ServerErrorHttpException
         */

        static public function getIds() {
            $filters = new Filters();
            return array_unique(ArrayHelper::getColumn($filters->getFiltersCategoriesIds(), 'idCategory'));
        }

        static public function getNames() {
            return \backend\models\Categories::getNameById(self::getIds());
        }
    }
