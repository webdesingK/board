<?php

namespace common\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class CategoriesQuery extends ActiveQuery {

    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

}