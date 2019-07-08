<?php


namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

class TestController extends Controller {

    public function actionTest() {

        Console::startProgress(0, 100);
        for ($n = 1; $n <= 100; $n++) {
            usleep(100000);
            Console::updateProgress($n, 100);
        }
        Console::endProgress();

    }

}