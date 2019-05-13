<?php

namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;


class AddController extends Controller {

    public function actionCreate($count) {

        $db = new Query();

        $categories = $db->select(['name', 'id'])->from('categories')->all();

        $countCategories = count($categories) - 1;

        $countString = 0;

        for ($i = 1; $i < $count + 1; $i++) {
            $randNum = rand(2, $countCategories);
            $res = $db->createCommand()->insert('ads', [
                'name' => 'ID объявления - ' . $i . ' Название категории - ' . $categories[$randNum]['name'],
                'id_category' => $categories[$randNum]['id']
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
