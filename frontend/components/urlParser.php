<?php


namespace frontend\components;

use frontend\models\Categories;
use frontend\models\Cities;

class urlParser {

    static public function getArray(array $get) {

        $rootCity = Cities::getRoot();
        $array = [
            'city' => [
                'default' => $rootCity,
                'current' => $rootCity
            ]
        ];

        if (!empty($get)) {

            if (isset($get['city'])) {

                $city = Cities::getCityByUrl($get['city']);
                if ($city) {
                    $array['city']['current'] = $city;
                }
                else {
                    $array['city'] = 'error';
                }

            }

            if (isset($get['categoryFirstLvl'])) {

                $categoryFirstLvl = Categories::getFirstLvlByUrl($get['categoryFirstLvl']);

                if ($categoryFirstLvl) {
                    $array['categories']['categoryFirstLvl'] = $categoryFirstLvl;
                    $array['currentCategory'] = $categoryFirstLvl;
                }
                else {
                    $array['categories'] = 'error';
                }

            }
            if (isset($get['categorySecondLvl']) && $array['categories'] != 'error') {

                $categorySecondLvl = Categories::getSecondLvlByNodeAndUrl($categoryFirstLvl, $get['categorySecondLvl']);

                if ($categorySecondLvl) {
                    $array['categories']['categorySecondLvl'] = $categorySecondLvl;
                    $array['currentCategory'] = $categorySecondLvl;
                }
                else {
                    $array['categories'] = 'error';
                }

            }

            if (isset($get['categoryThirdLvl']) && $array['categories'] != 'error') {

                $categoryThirdLvl = Categories::getThirdLvlByNodeAndUrl($categorySecondLvl, $get['categoryThirdLvl']);

                if ($categoryThirdLvl) {
                    $array['categories']['categoryThirdLvl'] = $categoryThirdLvl;
                    $array['currentCategory'] = $categoryThirdLvl;
                }
                else {
                    $array['categories'] = 'error';
                }

            }

        }

        return $array;

    }

}