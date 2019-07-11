<?php

    $this->title = 'Создание фильтров';

    //$this->registerCssFile('@web/css/main.css');
    $this->registerJsFile('@web/js/create-filters.js', ['depends' => 'backend\assets\AdminAsset']);
    $this->registerCssFile('@web/css/create-filters.css', ['depends' => 'backend\assets\AdminAsset']);

?>

<div class="container">
	<div class="row col-md-10">
		<div class="col-md-12 page-header">
			<h1 class="text-primary">Создание фильтров</h1>
		</div>
	</div>
  <div class="row well col-md-10">
    <div class="col-md-5">
      <strong class="alert alert-danger col-md-12 none">Введите название!!!</strong>
      <div class="form-group">
      	<label for="nameTable" class="text-info">Название таблицы</label>
      	<input class="form-control col-md-12" id="table-name-js" type="text" id="nameTable">
      </div>
    </div>
    <div class="col-md-7 list-group-css">
      <div class="col-md-11 form-group" id="add-list-js">
      	<div class="plug"></div>
      </div>
      <div class="filter__add col-md-12 text-success">
      	<p><i class="glyphicon glyphicon-plus"></i> - добавить пункт</p>
      </div>
    </div>
    <div class="btn-wrap col-md-12 text-right">
      <div class="btn btn-success" id="btn-save-js">Сохранить</div>
    </div>
  </div>
</div>
