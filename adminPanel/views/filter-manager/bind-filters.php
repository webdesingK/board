<?php

    /**
     * @var $this yii\web\View
     * @var $categories adminPanel\models\filtersManager\Categories
     */

    $this->title = 'Привязка фильтров';

    $this->registerCssFile('@web/css/bind-filters.css');
    $this->registerJsFile('@web/js/bind-filters.js');

?>

<div class="row col-md-12">
    <div class="col-md-12 page-header">
        <h1 class="text-primary">Привязка фильтров</h1>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-5 select-categories-css">
        <label class="text-info">Категории</label>
        <select class="form-control select">
            <option disabled="disabled" selected="selected">Выбрать категорию 1 уровня</option>

            <?php
                $categoriesFirstLvl = $categories->firstLvl;
            ?>
            <?php foreach ($categoriesFirstLvl as $category): ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach ?>

        </select>
        <select class="form-control select none">
            <option disabled="disabled" selected="selected">Выбрать категорию 2 уровня</option>
        </select>
        <select class="form-control select none">
            <option disabled="disabled" selected="selected">Выбрать категорию 3 уровня</option>
        </select>
    </div>
    <div class="col-md-7">
        <div class="col-md-12" id="add-list-js"></div>
        <div class="col-md-12 text-success none" id="add-filters-js">
            <p class="add-filters-css"><i class="glyphicon glyphicon-plus"></i> Добавить фильтр</p>
        </div>
    </div>
    <div class="col-md-12 btn-wrap-css text-right">
        <div class="btn btn-success none" id="btn-save-js">Сохранить</div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-info" id="message-js">
            <p><strong>Внимание! </strong><span>Обратите внимание на правильность заполнения полей</span></p>
        </div>
    </div>
</div>
