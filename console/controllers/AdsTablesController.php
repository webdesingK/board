<?php


namespace console\controllers;

use console\models\Categories;
use console\models\Cities;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use console\components\Translate;
use Yii;

class AdsTablesController extends Controller {

    public function actionCreateTables() {

        $db = new Query();

        $tableNames = self::getTableNames();

        echo "Проверяем наличие таблиц .";

        Console::hideCursor();

        for ($i = 1; $i <= 3; $i++) {
            Console::moveCursorTo(0);
            usleep(500000);
            Console::clearLine();
            if ($i == 1) {
                echo "Проверяем наличие таблиц ..";
            }
            elseif ($i == 2) {
                echo "Проверяем наличие таблиц ...";
            }
        }

        Console::moveCursorTo(0);
        Console::clearLine();

        if (!empty($tableNames)) {
            echo $this->ansiFormat(" Таблицы уже существуют:\n", Console::FG_RED);
            foreach ($tableNames as $tableName) {
                echo "\t$tableName\n";
            }
            self::actionDeleteTables();
        }

        $createdTables = [];
        $countCreatedTables = 0;

        echo " Создаем таблицы .";

        Console::hideCursor();

        for ($i = 1; $i <= 3; $i++) {
            Console::moveCursorTo(0);
            usleep(500000);
            Console::clearLine();
            if ($i == 1) {
                echo " Создаем таблицы ..";
            }
            elseif ($i == 2) {
                echo " Создаем таблицы ...";
            }
        }

        Console::moveCursorTo(0);
        Console::clearLine();

        $childrenOfRoot = Categories::getChildrenOfRoot();

        if (empty($childrenOfRoot)) {
            echo $this->ansiFormat(" Нет категорий для создания таблиц \n", Console::FG_RED);
            return 1;
        }

        $db->createCommand()->createTable('category_adsTable', [
            'id_category' => 'integer',
            'tableName' => 'string'
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')->execute();

        foreach ($childrenOfRoot as $key => $value) {

            $translateName = Translate::translateRuToEn($value['name']);

            $db->createCommand()->createTable("ads_$translateName", [
                'id' => 'pk',
                'title' => 'string',
                'price' => 'string',
                'id_category' => 'integer',
                'id_city' => 'integer'
            ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')->execute();

            $db->createCommand()->insert('category_adsTable', [
                'id_category' => $value['id'],
                'tableName' => "ads_$translateName"
            ])->execute();

            array_push($createdTables, "ads_$translateName");
            $countCreatedTables++;
        }

        if ($countCreatedTables > 0) {
            echo $this->ansiFormat(" Созданы таблицы: \n", Console::FG_GREEN);
            foreach ($createdTables as $table) {
                echo "\t$table\n";
            }
            echo $this->ansiFormat(" Количество - $countCreatedTables \n", Console::FG_GREEN);
        }
    }

    public function actionAddAds() {

        $adsCount = Console::input('Введи количество объявлений для записи в каждую таблицу: ');

        if ($adsCount) {
            if (is_numeric($adsCount)) {

                $db = new Query();

                self::actionCreateTables();
                echo $this->ansiFormat("\v Запись значений в бд ... \n\v", Console::FG_GREEN);

                $childrenOfRootCategories = Categories::getChildrenOfRoot();
                $childrenOfRootCities = Cities::getChildrenOfRoot();
                $countCities = count($childrenOfRootCities) - 1;

                Console::startProgress(0, $adsCount);

                $countString = 0;

                foreach ($childrenOfRootCategories as $item) {

                    $childrenCategories = Categories::getChildren($item['id']);
                    $translateName = Translate::translateRuToEn($item['name']);

                    $countCategories = count($childrenCategories) - 1;

                    $priceArray = self::getPriceArray();
                    $countPriceArray = count($priceArray) - 1;

                    for ($i = 0; $i < $adsCount; $i++) {
                        $randNumCategory = rand(0, $countCategories);
                        $randNumCity = rand(0, $countCities);
                        $randPrice = rand(0, $countPriceArray);
                        $res = $db->createCommand()->insert('ads_' . $translateName, [
                            'title' => 'Продам ' . $childrenCategories[$randNumCategory]['name'],
                            'price' => $priceArray[$randPrice],
                            'id_category' => $childrenCategories[$randNumCategory]['id'],
                            'id_city' => $childrenOfRootCities[$randNumCity]['id']
                        ])->execute();
                        if ($res) {
                            $countString++;
                            Console::updateProgress($i, $adsCount);
                        }
                    }
                    Console::endProgress(true);
                    echo $this->ansiFormat(" В таблицу ads_$translateName записано $adsCount объявлений \n", Console::FG_GREEN);
                }

                echo $this->ansiFormat("\v Всего записано $countString объявлений\n\v", Console::FG_GREEN);

            }
            else {
                echo $this->ansiFormat(" Вводи только числа \n", Console::FG_RED);
                return 1;
            }
        }
        else {
            echo $this->ansiFormat(" Нет значения \n", Console::FG_RED);
            return 1;
        }
        Console::showCursor();
    }

    public function actionDeleteTables() {

        $db = new Query();
        $tableNames = self::getTableNames();
        if (!empty($tableNames)) {
            $countDeletedTables = 0;
            $deletedTables = [];
            foreach ($tableNames as $tableName) {
                $db->createCommand()->dropTable($tableName)->execute();
                array_push($deletedTables, $tableName);
                $countDeletedTables++;
            }

            echo $this->ansiFormat(" Удаляем . ", Console::FG_RED);

            Console::hideCursor();

            for ($i = 1; $i <= 3; $i++) {
                Console::moveCursorTo(0);
                usleep(500000);
                Console::clearLine();
                if ($i == 1) {
                    echo $this->ansiFormat(" Удаляем .. ", Console::FG_RED);
                }
                elseif ($i == 2) {
                    echo $this->ansiFormat(" Удаляем ... ", Console::FG_RED);
                }
            }

            Console::moveCursorTo(0);
            Console::clearLine();

            $db->createCommand()->dropTable('category_adsTable')->execute();

            echo $this->ansiFormat(" Удалены таблицы: \n", Console::FG_GREEN);

            foreach ($deletedTables as $deletedTable) {
                echo "\t$deletedTable\n";
            }

            echo $this->ansiFormat(" Количество - $countDeletedTables \n", Console::FG_GREEN);
        }
        else {
            echo " Нет таблиц для удаления .";
            return 1;
        }
        Console::showCursor();
    }

    public function getTableNames() {
        $db = new Query();
        return ArrayHelper::getColumn($db->select('table_name')->from('information_schema.tables')->where(['like', 'table_name', 'ads%', false])->all(), 'table_name');
    }

    public function getPriceArray() {

        $minPrice = 10;
        $priceArray = [];

        for ($i = 0; $i < 1000; $i++) {
            array_push($priceArray, $minPrice);
            $minPrice += 10;
        }

        return $priceArray;
    }

}