<?php


    namespace adminPanel\models\filtersManager;

    use yii\helpers\ArrayHelper;

    class Filters extends \common\models\filters\Filters {

        /**
         * @param $rusName
         * @return array|\yii\db\ActiveRecord[]
         */

        public function findAllByRusName($rusName) {
            return static::find()
                ->where('rusName = :rusName', [':rusName' => $rusName])
                ->all();
        }

        /**
         * @return mixed
         */

        public function getAllRusNames() {
            $rusNames = static::find()
                ->select('rusName')
                ->asArray()
                ->all();
            return ArrayHelper::getColumn($rusNames, 'rusName');
        }

        /**
         * @param $rusName string
         * @return string
         */

        public function getLatNameByRusName($rusName) {
            return static::findOne(['rusName' => $rusName])->latName;
//                ->select('latName')
//                ->where('rusName = :rusName')
//                ->addParams([':rusName' => $rusName])
//                ->one()
//                ->latName;
        }

    }