<?php


    namespace adminPanel\models\filters;

    use yii\db\ActiveRecord;

    class FilterCategories extends ActiveRecord {

        static public function tableName() {
            return 'filters.filters';
        }



    }