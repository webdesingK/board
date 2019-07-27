<?php


namespace console\controllers;

use yii\console\Controller;
use yii\db\Migration;

class TestController extends Controller {

    public function actionRun() {

        try {

            $db = \Yii::$app->db;

            $migration = new Migration();

            $db
                ->createCommand('CREATE DATABASE filters CHARACTER SET utf8 COLLATE utf8_general_ci')
                ->execute();

            $db
                ->createCommand()
                ->createTable('filters.filters', [
                    'id' => $migration->primaryKey()->unsigned(),
                    'rusName' => $migration->string(50),
                    'latName' => $migration->string(50),
                    'url' => $migration->string(50),
                    'idParentCategory' => $migration->smallInteger()
                ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')
                ->execute();
            $db
                ->createCommand()
                ->createTable('filters.filtersCategories', [
                    'idFilter' => $migration->smallInteger()->unsigned(),
                    'idCategory' => $migration->smallInteger()->unsigned(),
                ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB')
                ->execute();

            echo 'Усё окей';
            echo "\n";

        }
        catch (\Throwable $e) {
            echo $e->getMessage();
            echo "\n";
        }

    }

}