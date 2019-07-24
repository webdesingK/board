<?php

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
			<option value="2">трусы</option>
			<option value="3">одежда</option>
			<option value="4">одежда</option>
			<option value="5">одежда</option>
		</select>
		<select class="form-control select none">
			<option disabled="disabled" selected="selected">Выбрать категорию 2 уровня</option>
		</select>
		<select class="form-control select none">
			<option disabled="disabled" selected="selected">Выбрать категорию 3 уровня</option>
		</select>
	</div>
	<div class="col-md-7 table-responsive">
		<table class="table table-hover-css">
			<thead>
				<tr>
					<th class="col-md-6">Url</th>
					<th class="col-md-6">Название фильтра</th>
				</tr>
			</thead>
			<tbody id="add-list-js">
			</tbody>
		</table>
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

<!-- <div class="input-group">
    <span class="input-group-addon counts">1</span>
    <select class="form-control" id="select-filters-js">
        <option value="1">1</option>
        <option value="2" selected="selected">размеры</option>
        <option value="3">2</option>
    </select>
    <span class="input-group-addon" title="Удалить пункт">
        <i class="glyphicon glyphicon-remove-circle text-danger"></i>
    </span>
</div> -->