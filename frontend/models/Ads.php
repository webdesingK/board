<?php

namespace frontend\models;

use yii\db\Query;
use yii\helpers\ArrayHelper;
use frontend\models\Categories;
use yii2tech\filedb;
class Ads {

    static private function getTableNames() {
        return ArrayHelper::getColumn((new Query())->select('table_name')->from('information_schema.tables')->where(['like', 'table_name', 'ads%', false])->all(), 'table_name');
    }

    static public function getAdsData($url) {

        $tableNames = self::getTableNames();
        $db = new Query();
        $ads = [];

        if (empty($tableNames)) return $ads;

        $categories = Categories::getAllData();
        $categoriesNamesById = ArrayHelper::map($categories, 'id', 'name');
        $count = 0;

        if (!isset($url['category'])) {
            foreach ($tableNames as $tableName) {
                $oneTableData = $db->select(['title', 'price', 'id_category'])->from($tableName)->all();
                foreach ($oneTableData as $value) {
                    $ads[$count] = $value;
                    $ads[$count]['category'] = $categoriesNamesById[$value['id_category']];
                    unset($ads[$count]['id_category']);
                    $count++;
                }
            }
        }
        else {
            $childrenCategory = Categories::getChildrenById($url['category']['id'], 2);
            $arrayIdsCategories = [$url['category']['id']];

            foreach ($childrenCategory as $item) {
                array_push($arrayIdsCategories, $item['id']);
            }

            foreach ($tableNames as $tableName) {
                $oneTableData = $db->from($tableName)->where(['in', 'id_category', $arrayIdsCategories])->all();
                foreach ($oneTableData as $value) {
                    $ads[$count] = $value;
                    $ads[$count]['category'] = $categoriesNamesById[$value['id_category']];
                    unset($ads[$count]['id_category']);
                    $count++;
                }
            }

        }

        return $ads;

    }

}
