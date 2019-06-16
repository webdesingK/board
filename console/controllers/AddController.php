<?php

namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;
use console\models\Categories;
use Yii;

class AddController extends Controller {

    public function actionCreate($countOfStrings) {

        $db = new Query;
        $categories = new Categories;

        $childrenOfRoot = self::getChildrenOfRoot();

        if (empty($childrenOfRoot)) {
            echo 'Нет категорий для создания таблиц' . "\n";
            return;
        }

        $countCreateTable = 0;
        $allCount = 0;

        $priceArray = self::getPriceArray();
        $countPriceArray = count($priceArray) - 1;

        foreach ($childrenOfRoot as $key => $item) {

            if (Yii::$app->db->createCommand("show tables from board like 'ads_" . $item['name'] . "'")->execute()) {
                echo 'Таблица ads_' . $item['name'] . ' уже существует' . "\n";
                continue;
            }

            $db->createCommand()->createTable('ads_' . $item['name'], [
                'id' => 'pk',
                'title' => 'string',
                'price' => 'string',
                'id_category' => 'integer'
            ])->execute();

            echo 'Создана таблица - ads_' . $item['name'] . "\n";

            $countCreateTable++;

            $childrenCategories = $categories->getChildren($item['id']);

            $countCategories = count($childrenCategories) - 1;
            $countString = 0;

            for ($i = 0; $i < $countOfStrings; $i++) {
                $randNumCategory = rand(0, $countCategories);
                $randPrice = rand(0, $countPriceArray);
                $res = $db->createCommand()->insert('ads_' . $item['name'], [
                    'title' => 'Продам ' . $childrenCategories[$randNumCategory]['name'],
                    'price' => $priceArray[$randPrice],
                    'id_category' => $childrenCategories[$randNumCategory]['id']
                ])->execute();
                if ($res) {
                    $countString++;
                    $allCount++;
                }
            }
            echo 'В таблицу - ads_' . $item['name'] . ' добавлено строк - ' . $countString . "\n";
        }

        if ($countCreateTable == 0) {
            echo 'Выполни команду "php yii create/delete для удаления таблиц"' . "\n";
        }
        else {
            echo "Таблиц создано - $countCreateTable \n";
            echo "Всего добавлено строк - $allCount \n";
        }

        return;

    }

    public function actionDelete() {
        $db = new Query();
        $children = self::getChildrenOfRoot();
        $count = 0;
        foreach ($children as $child) {
            $db->createCommand()->dropTable('ads_' . $child['name'])->execute();
            echo 'Удалена таблица - ads_' . $child['name'] . "\n";
            $count++;
        }
        echo "Удалено таблиц - $count \n";
    }

    private function getChildrenOfRoot() {
        $children = Categories::getChildrenOfRoot();
        foreach ($children as $key => $child) {
            $children[$key] = [
                'id' => $child->id,
                'name' => self::translate($child->name)
            ];
        }
        return $children;
    }

    private function translate($string) {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        $newString = strtr($string, $converter);
        $newString = strtolower($newString);
        $newString = preg_replace('/\s/', '-', $newString);
        return $newString;
    }

    private function getPriceArray() {

        $minPrice = 10;
        $priceArray = [];

        for($i = 0; $i < 1000; $i++) {
            array_push($priceArray, $minPrice);
            $minPrice += 10;
            if (strlen($minPrice) > 3) {
                $priceArray[$i] = substr_replace($minPrice, '.', -3, 0);
            }
        }

        return $priceArray;
    }

}
