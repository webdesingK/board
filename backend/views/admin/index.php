<?php

$this->title = 'Главная';
$this->registerCssFile('@web/css/sandbox.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/js/sandbox.js', ['depends' => 'backend\assets\AppAsset']);

?>


<div class="page-header">
	<h1 class="text-info text-center">Песочница</h1>
</div>

<div class="slide__top col-md-12">
	<div class="btn-categories btn btn-info">Категории</div>
	<div class="slide__top-categories">
		<ul class="slide__top-list">
			<li><a href="sdsd">Одежда</a></li>
			<li><a href="#">Недвижимость</a></li>
			<li><a href="#">Недвижимость</a></li>
			<li><a href="#">Недвижимость</a></li>
			<li><a href="#">Недвижимость</a></li>
			<li><a href="#">Авто</a></li>
			<li><a href="#">Техника</a></li>
			<li><a href="#">Техника</a></li>
			<li><a href="#">Техника</a></li>
			<li><a href="#">Техника</a></li>
			<li><a href="#">Женская гардероб</a></li>
			<li><a href="#">Разное</a></li>
			<li><a href="#">тотемы</a></li>
			<li><a href="#">приборы</a></li>
			<li><a href="#">Детский мир</a></li>
			<li><a href="#">Спецодежда</a></li>
			<li><a href="#">Тату и татуаж</a></li>
			<li><a href="#">парфюмерия</a></li>
		</ul>
	</div>
	<div class="slide__top-arrow">
		<div class="arrow__left">
			<i class="glyphicon glyphicon-chevron-left"></i>
		</div>
		<div class="arrow__right">
			<i class="glyphicon glyphicon-chevron-right"></i>
		</div>
	</div>
</div>