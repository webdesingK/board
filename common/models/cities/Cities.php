<?php

namespace common\models\cities;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class Cities extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'cities';
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
        return new CitiesQuery(get_called_class());
    }

}
