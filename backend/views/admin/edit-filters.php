<?php

    $this->title = 'Редактирование фильтров';

    $this->registerJsFile('@web/js/edit-filters.js', ['depends' => 'backend\assets\AppAsset']);
    $this->registerCssFile('@web/css/edit-filters.css', ['depends' => 'backend\assets\AppAsset']);

    $names = \backend\models\Filters::getNames();
?>

<div class="row col-md-12">
    <div class="col-md-12 page-header">
        <h1 class="text-primary">Редактирование фильтров</h1>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-5 select-categories-css">
        <label class="text-info">Фильтры</label>
        <select class="form-control" id="select-categories-js">
            <option disabled="disabled" selected="selected">Выбрать фильтр</option>
            <?php foreach ($names as $name): ?>

                <option><?= $name['rus'] ?></option>

            <?php endforeach ?>
        </select>
    </div>
    <div class="col-md-7 table-responsive">
        <table class="table table-hover-css">
            <thead>
            <tr>
                <th class="col-md-6">Пункты</th>
            </tr>
            </thead>
            <tbody id="add__list-js">
            </tbody>
        </table>
        <div class="col-md-12 text-success" id="filter__add-js">
            <p class="filter__add-css"><i class="glyphicon glyphicon-plus"></i> добавить фильтр</p>
        </div>
    </div>
    <div class="col-md-12 btn-wrap-css text-right">
        <div class="btn btn-success" id="btn-save-js">Сохранить</div>
        <div class="btn btn-danger" id="btn-delete-js">Удалить фильтр</div>
    </div>
    <div class="col-md-12">
        <div class="alert alert-info" id="message-js">
            <p><strong>Внимание! </strong><span>Обратите внимание на правильность заполнения полей</span></p>
        </div>
    </div>
</div>