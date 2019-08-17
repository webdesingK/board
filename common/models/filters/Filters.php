<?php


    namespace common\models\filters;

    use yii\db\ActiveRecord;

    /**
     * Class Filters
     * @package common\models\filters
     *
     * @property string $rusName
     * @property string $latName
     * @property string $url
     * @property integer $idParentCategory
     */
    class Filters extends ActiveRecord {

        /**
         * @var $title mixed
         */

        public $title = [];

        /**
         * @return string
         */

        public static function tableName() {
            return 'filters.filters';
        }

        /**
         * @return array
         */

        public function rules() {
            return [
                [['rusName', 'latName', 'url', 'idParentCategory'], 'required'],
                [['rusName', 'url'], 'string', 'length' => [1, 50]],
                ['idParentCategory', 'integer'],
                ['latName', 'safe'],
//                ['title', 'each', 'rule' => ['required']]
            ];
        }

//        public function attributeLabels() {
//
//            return [
//                'rusName' => 'Название фильтра',
//                'url' => 'URL',
//                'idParentCategory' => 'Категория'
//            ];
//
//        }
    }