<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class Categories extends ActiveRecord {

    public $parent;

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

    /**
     * {@inheritdoc}
     */
//    public function rules() {
//        return [
//            [['lft', 'rgt', 'depth', 'name'], 'required'],
//            [['lft', 'rgt', 'depth'], 'integer'],
//            [['name'], 'string', 'max' => 255],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
//    public function attributeLabels() {
//        return [
//            'name' => 'Name',
//        ];
//    }
}