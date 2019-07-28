<?php

    /**
     * @var $this yii\web\View
     * @var $categories adminPanel\models\filters\Categories
     */

    $this->title = 'Создание фильтров';

    $this->registerJsFile('@web/js/create-filters.js', ['depends' => 'adminPanel\assets\AppAsset']);
    $this->registerCssFile('@web/css/create-filters.css', ['depends' => 'adminPanel\assets\AppAsset']);

?>

<div class="row col-md-10">
    <div class="col-md-12 page-header">
        <h1 class="text-primary">Создание фильтров</h1>
    </div>
</div>
<div class="row col-md-10">
    <div class="col-md-5 input-categories-css">
        <div class="form-group">
            <label class="text-info">Название фильтра</label>
            <input class="form-control col-md-12" id="table__name-js" type="text">
        </div>
        <div class="form-group">
            <label class="text-info">URL</label>
            <input class="form-control col-md-12" id="table__url-js" type="text">
        </div>
        <div class="form-group">
            <label for="nameTable" class="text-info">Выберите категорию</label>
            <select class="form-control" id="select-catigories">
                <option disabled="disabled" selected="selected">Выберите категорию</option>
                <?php
                    $categoriesFirstLvl = $categories->firstLvl;
                ?>
                <?php foreach ($categoriesFirstLvl as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="col-md-7 list-group-css">
        <table class="table table-hover-css">
            <thead>
            <tr>
                <th>Пункты</th>
            </tr>
            </thead>
            <tbody id="add__list-js">
            </tbody>
        </table>
        <div class="filter__add-css col-md-12 text-success" id="filter__add-js">
            <p><i class="glyphicon glyphicon-plus"></i> Добавить пункт</p>
        </div>
    </div>
    <div class="btn-wrap-css col-md-12 text-right">
        <div class="btn btn-success" id="btn-save-js">Сохранить</div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-info" id="message-js">
            <p><strong>Внимание! </strong><span>Обратите внимание на правильность заполнения полей</span></p>
        </div>
    </div>
</div>
