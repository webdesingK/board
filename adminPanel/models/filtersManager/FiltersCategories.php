<?php


    namespace adminPanel\models\filters;

    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\web\ServerErrorHttpException;

    class FiltersCategories extends ActiveRecord {

        public static function tableName() {
            return 'filters.filtersCategories';
        }

        public function getIds($name) {
            try {
                $ids = self::findAll();
                return array_unique(ArrayHelper::getColumn($ids, $name));
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException($e);
            }
        }

    }