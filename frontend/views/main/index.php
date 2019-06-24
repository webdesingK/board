<div class="content__wrap-header">
        <p>Всего объявлений - <?= count($adsData) ?></p>
    <div class="view">
        <div id="view-line">▤</div>
        <div id="view-square">▦</div>
    </div>
</div>

<? foreach($adsData as $value): ?>

    <div class="content__wrap-item">
        <div class="item__img mini__slide-4">
            <div class="mini__slide">
                <img src="/img/photo1.jpg" alt="">
                <div class="mini__slide-after"></div>
            </div>
            <div class="mini__slide">
                <img src="/img/photo2.jpg" alt="">
                <div class="mini__slide-after"></div>
            </div>
            <div class="mini__slide">
                <img src="/img/photo5.jpg" alt="">
                <div class="mini__slide-after"></div>
            </div>
            <div class="mini__slide">
                <img src="/img/photo3.jpg" alt="">
                <div class="mini__slide-after"></div>
            </div>
        </div>
        <div class="item__content">
            <a href="#" class="item__content-name"><?= $value['title'] ?></a>
            <p class="item__content-city"><?= $value['city'] ?></p>
            <p class="item__content-price"><?= $value['price'] ?> руб.</p>
            <p class="item__content-category"><?= $value['category']?></p>
        </div>
    </div>

<? endforeach ?>

<? $this->params['url'] = $url ?>
