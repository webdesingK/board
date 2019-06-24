<?
    dump($url);
?>


    <div class="content__wrap-header">
        <p>Всего объявлений - <?= count($adsData) ?></p>
        <div class="view">
            <div id="view-line">▤</div>
            <div id="view-square">▦</div>
        </div>
    </div>

<? foreach ($adsData as $value): ?>

    <div class="content__wrap-item">
        <a href="#" class="name-item"><?= $value['title'] ?></a>
        <p class="item-city"><?= $value['city'] ?></p>
        <p class="item-price"><?
            if (strlen($value['price']) > 3) {
                echo substr_replace($value['price'], '.', -3, 0);
            }
            else {
                echo $value['price'];
            }
            ?> руб.</p>
        <p class="item-category"><?= $value['category'] ?></p>
    </div>

<? endforeach ?>

<? $this->params['url'] = $url ?>

<?
?>