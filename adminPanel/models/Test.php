<?php


    namespace adminPanel\models;

    use yii\base\Model;

    class Test extends Model{

        public $name;
        public $age;

        public function rules() {
            return [
                ['name', 'required'],
//                ['age', 'integer']
            ];
        }

    }