<?php

    $this->title = 'Создание фильтров';

    //$this->registerCssFile('@web/css/main.css');
    $this->registerJsFile('@web/js/create-filters.js', ['depends' => 'backend\assets\AdminAsset']);
    $this->registerCssFile('@web/css/create-filters.css', ['depends' => 'backend\assets\AdminAsset']);

?>

<div class="wrapeer">
    <div class="wrap">
        <div class="wrap__left">
            <div class="name-error none">Введите название!!!</div>
            <input class="filter-name" type="text" placeholder="Название фильтра">
        </div>
        <div class="wrap__right">
            <div class="wrap__right-add">
                <div class="filter__add" title="добавить в список фильтра">✚</div>
            </div>
            <div class="btn-wrap">
                <div class="btn-save">Сохранить</div>
            </div>
        </div>
    </div>
</div>