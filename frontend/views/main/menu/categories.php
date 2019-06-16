<?php

use frontend\models\Categories;
use yii\helpers\Html;

$categories = Categories::getAllData();

echo Html::beginTag('ul', ['class' => 'menu__first']);

foreach ($categories as $key => $category) {
    if ($category['depth'] == $lvl) {
        if ($key > 0) {
            echo Html::endTag('li');
        }
    }
    elseif ($category['depth'] > $lvl) {
        if ($category['depth'] == 2) {
            echo Html::beginTag('ul', ['class' => 'menu__second']);
        }
        elseif ($category['depth'] == 3) {
            echo Html::beginTag('ul', ['class' => 'menu__third']);
        }
    }
    else {
        echo Html::endTag('li');
        for ($i = $lvl - $category['depth']; $i; $i--) {
            echo Html::endTag('ul');
            echo Html::endTag('li');
        }
    }

    echo Html::beginTag('li');

    $linkContent = Html::encode($category['name']);
    $linkUrl = !empty($url) ? '/' . $url['city']['url'] . '/' . $category['url'] : '/Все-города/' . $category['url'];

    if ($category['depth'] == 1) {
        $linkContent .= Html::tag('span', '>');
    }

    echo Html::a($linkContent, $linkUrl);
    $lvl = $category['depth'];
}

for ($i = $lvl; $i; $i--) {
    echo Html::endTag('li');
    echo Html::endTag('ul');
}


