<?php


namespace common\models\filters;

use yii\db\ActiveRecord;

class FiltersCategories extends ActiveRecord {

    public static function tableName() {
        return 'filters.filters_categories';
    }

}