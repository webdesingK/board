<br>
Всего объявлений - <?= count($adsData) ?>
<hr>

<? foreach($adsData as $v): ?>

    <div class="content__wrap-item">
    	<a href="#" class="name-item">name</a>
    	<p class="item-city">city</p>
    	<p class="item-price">price</p>
    	<p class="item-category">category</p>
    	<?= $v['name'] ?>
    </div>


<? endforeach ?>