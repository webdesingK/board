<?php


    namespace adminPanel\models\filters;

    use common\models\categories\Categories;
    use yii\helpers\ArrayHelper;

    class CategoryFilters extends Categories {

        public function attributeLabels() {
            return [
                'name' => 'Название категории',
            ];
        }

        static public function getIds() {
            $filters = new ManagerFilters();
            return array_unique(ArrayHelper::getColumn($filters->getFiltersCategoriesIds(), 'idCategory'));
        }

        static public function getNames() {
            return \backend\models\Categories::getNameById(self::getIds());
        }
    }