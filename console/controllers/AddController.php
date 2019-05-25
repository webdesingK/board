<?php

namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;


class AddController extends Controller {

    public function actionCreate($count) {

        $db = new Query();

        $categories = $db->select(['name', 'id'])->from('categories')->all();
        $cities = $db->select(['name', 'id'])->from('cities')->where('id != 1')->all();

        $countCategories = count($categories) - 1;
        $countCities = count($cities) -1;

        $countString = 0;

        for ($i = 1; $i < $count + 1; $i++) {
            $randNumCategory = rand(2, $countCategories);
            $randNumCity = rand(2, $countCities);
            $res = $db->createCommand()->insert('ads', [
                'name' => 'ID объявления - ' . $i . ' Название категории - ' . $categories[$randNumCategory]['name'] . '; Город - ' . $cities[$randNumCity]['name'],
                'id_category' => $categories[$randNumCategory]['id'],
                'id_city' => $cities[$randNumCity]['id']
            ])->execute();
            if ($res) {
                $countString++;
            }
        }

        echo " Передано $count строк. Добавлено $countString строк \r \n";

    }

    public function actionDelete() {

        $db = new Query();
        $db->createCommand()->truncateTable('ads')->execute();
    }

}
