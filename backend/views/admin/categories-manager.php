<?php
/**
 * @var $model \backend\models\Categories;
 */

$this->title = 'Манагер категорий';

use yii\helpers\Html;

//$this->registerCssFile('@web/css/libs.css');
//$this->registerCssFile('@web/css/main.css');
$this->registerJsFile('@web/js/common.js', ['depends' => 'backend\assets\AppAsset']);

?>


    <?php if (!Yii::$app->request->isAjax): ?>

    <div class="category">

        <div class="category__main">

    <?php endif ?>

            <?php

            $categories = $model->createArray();
            $lvl = 0;

            echo Html::beginTag('div', ['class' => 'category__list', 'data-id' => 1]);

            foreach ($categories as $key => $category) {

                if ($category['depth'] == $lvl) {
                    if ($key > 0) {
                        echo Html::endTag('div');
                        echo Html::beginTag('div', ['class' => 'category__list ' . $category['class'], 'data-id' => $category['id']]);
                    }
                }
                elseif ($category['depth'] > $lvl) {
                    echo Html::beginTag('div', ['class' => 'category__list ' . $category['class'], 'data-id' => $category['id']]);
                }
                else {
                    echo Html::endTag('div');
                    for ($i = $lvl - $category['depth']; $i; $i--) {
                        echo Html::endTag('div');
                    }
                    echo Html::beginTag('div', ['class' => 'category__list ' . $category['class'], 'data-id' => $category['id']]);
                }

                echo Html::beginTag('div', ['class' => 'category__list-block']);
                if ($category['id'] == 1) {
                    echo Html::tag('span', Html::encode($category['name']) . ' (' . (count($categories) - 1) . ')', ['class' => 'name-category']);
                }
                else {
                    echo Html::tag('span', Html::encode($category['name']), ['class' => 'name-category']);
                }
                echo Html::tag('span', '&plus;', ['class' => 'add-category', 'title' => 'Добавить новую категорию']);
                echo Html::tag('span', '✎', ['class' => 'edit-category', 'title' => 'Редактирование названия категории']);
                echo Html::tag('span', '✘', ['class' => 'del-category', 'title' => 'Удалить категорию и подкатегории']);
                echo Html::checkbox(null, $category['checkbox']['checked'], ['class' => 'checkbox', 'value' => null, 'title' => $category['checkbox']['title'], 'data-active' => $category['checkbox']['data-active']]);
                echo Html::tag('span', '➭', ['class' => 'motion__up-category', 'title' => 'Переместить вверх']);
                echo Html::tag('span', '➭', ['class' => 'motion__down-category', 'title' => 'Переместить вниз']);
                echo Html::tag('span', '▶', ['class' => 'tabs-category', 'title' => 'Развернуть']);
                echo Html::endTag('div');

                $lvl = $category['depth'];

            }

            for ($i = $lvl + 1; $i; $i--) {
                echo Html::endTag('div');
            }

            ?>

    <?php if (!Yii::$app->request->isAjax): ?>

        </div>

    </div>

    <?php endif ?>



<!-- /.category -->