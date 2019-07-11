<?php

$this->title = 'Привязка фильтров';

$this->registerCssFile('@web/css/bind-filters.css');
$this->registerJsFile('@web/js/bind-filters.js');

?>

<div class="container">
	<div class="row col-md-10">
		<div class="col-md-12 page-header">
			<h1 class="text-primary">Привязка фильтров</h1>
		</div>
	</div>
	<div class="row well col-md-10">
		<div class="col-md-4">
			<select class="form-control select-categories-css" id="select-categories-js">
				<option disabled="disabled" selected="selected">Выбрать категорию</option>
				<optgroup label="1 уровень категории">
					<option value="id категории">трусы</option>
					<option value="id категории">одежда</option>
					<option value="id категории" class="pl">одежда</option>
					<option value="id категории" class="pl">одежда</option>
				</optgroup>
			</select>
		</div>
		<div class="col-md-8 table-responsive">
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
			<div class="col-md-12 text-success" id="add-filters-js">
				<p class="add-filters-css"><i class="glyphicon glyphicon-plus"></i> добавить фильтр</p>
			</div>
		</div>
		<div class="col-md-12 text-right">
		  <div class="btn btn-success" id="btn-save-js">Сохранить</div>
		</div>
	</div>
</div>

