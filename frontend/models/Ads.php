<?php

namespace frontend\models;

use yii\db\Query;
use yii\helpers\ArrayHelper;

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

        $cities = Cities::getAllData();
        $citiesNamesById = ArrayHelper::map($cities, 'id', 'name');

        $count = 0;

        $price = [
            'min' => 0,
            'max' => 1000000
        ];

        if (isset($url['filters']['price'])) {
            $price['min'] = (int)$url['filters']['price']['min'];
            $price['max'] = (int)$url['filters']['price']['max'];
        }

        if (isset($url['category'])) {

            $childrenIds = Categories::getChildrenIds($url['category']);

            if (empty($childrenIds)) {
                $childrenIds = [$url['category']['id']];
            }

            $parentId = Categories::getParentId($url['category']['id']);

            $tableName = $db->select('tableName')->from('category_adsTable')->where(['id_category' => $parentId])->one()['tableName'];

            $tableData = null;

            if ($url['city']['name'] != 'Все города') {
                $tableData = $db->select(['title', 'price', 'id_category', 'id_city'])->from($tableName)->where(['in', 'id_category', $childrenIds])->andWhere(['id_city' => $url['city']['id']])->andWhere(['between', 'price', $price['min'], $price['max']])->all();
            }
            else {
                $tableData = $db->select(['title', 'price', 'id_category', 'id_city'])->from($tableName)->where(['in', 'id_category', $childrenIds])->andWhere(['between', 'price', $price['min'], $price['max']])->all();
            }

            foreach ($tableData as $tableDatum) {
                $ads[$count] = $tableDatum;
                $ads[$count]['category'] = $categoriesNamesById[$tableDatum['id_category']];
                $ads[$count]['city'] = $citiesNamesById[$tableDatum['id_city']];
                unset($ads[$count]['id_city']);
                unset($ads[$count]['id_category']);
                $count++;
            }

        }
        else {
            $oneTableData = null;
            foreach ($tableNames as $tableName) {
                if ($url['city']['name'] != 'Все города') {
                    $oneTableData = $db->select(['title', 'price', 'id_category', 'id_city'])->from($tableName)->where(['id_city' => $url['city']['id']])->andWhere(['between', 'price', $price['min'], $price['max']])->all();
                }
                else {
                    $oneTableData = $db->select(['title', 'price', 'id_category', 'id_city'])->from($tableName)->where(['between', 'price', $price['min'], $price['max']])->all();
                }
                foreach ($oneTableData as $value) {
                    $ads[$count] = $value;
                    $ads[$count]['category'] = $categoriesNamesById[$value['id_category']];
                    $ads[$count]['city'] = $citiesNamesById[$value['id_city']];
                    unset($ads[$count]['id_city']);
                    unset($ads[$count]['id_category']);
                    $count++;
                }
            }
        }

        return $ads;

    }

}
