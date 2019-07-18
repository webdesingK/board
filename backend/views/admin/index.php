<?php

$this->title = 'Главная';
use dastanaron\translit\Translit;
use yii\helpers\BaseInflector;
$str = 'Размер мужской верх';

$translit = new Translit();


$na = $translit->translit($str, false, 'ru-en');
//
$na = BaseInflector::variablize($na);

//$na = BaseInflector::camel2id($na, ' ');

//$na = BaseInflector::classify($na);

    dump($na);


?>


<h1 style="margin-left: 200px">Главная</h1>