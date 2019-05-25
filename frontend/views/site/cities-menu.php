<?php

use frontend\models\Cities;
use yii\helpers\Html;

$cities = Cities::createArray();
$lvl = 1;

echo Html::beginTag('ul', ['class' => 'city__first']);

foreach ($cities as $key => $city) {
    if ($city['depth'] == $lvl) {
        if ($key > 0) {
            echo Html::endTag('li');
        }
    }
    elseif ($city['depth'] > $lvl) {
        if ($city['depth'] == 2) {
            echo Html::beginTag('ul', ['class' => 'city__second']);
        }
        elseif ($city['depth'] == 3) {
            echo Html::beginTag('ul', ['class' => 'city__thrid']);
        }
    }
    else {
        echo Html::endTag('li');
        for ($i = $lvl - $city['depth']; $i; $i--) {
            echo Html::endTag('ul');
            echo Html::endTag('li');
        }
        if ($city['depth'] == 1) {
            echo Html::endTag('ul');
            echo Html::beginTag('ul', ['class' => 'city__first']);
        }
    }

    $linkUrl = !$this->context->urlCategory ? '/' . $city['name'] : '/' . $city['name'] . '/' . $this->context->urlCategory;

    echo Html::beginTag('li');
    echo Html::a(Html::encode($city['name']), $linkUrl);
    $lvl = $city['depth'];
}

for ($i = $lvl; $i; $i--) {
    echo Html::endTag('li');
    echo Html::endTag('ul');
}

