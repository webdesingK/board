
<div class="content__wrap-header">
	<p>Всего объявлений - <?= count($adsData) ?></p>
	<div class="view">
		<div id="view-line">▤</div>
		<div id="view-square">▦</div>
	</div>
</div>

<? foreach($adsData as $v): ?>

    <div class="content__wrap-item">
    	<a href="#" class="name-item">name</a>
    	<p class="item-city">city</p>
    	<p class="item-price">price</p>
    	<p class="item-category">category</p>
    	<?= $v['name'] ?>
    </div>


<? endforeach ?>