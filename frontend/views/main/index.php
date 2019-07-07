<?

//dump($url);

// use yii2tech\filedb\Query;

// $fdb = new Query();

// $res = $fdb->from('User')->all();

// dump($url);

?>


    <div class="content__wrap-header">
        <p>Всего объявлений - <?= count($adsData) ?></p>
        <div class="view">
            <div id="view-line">▤</div>
            <div id="view-square">▦</div>
        </div>
    </div>

<? foreach ($adsData as $key => $value): ?>

    <div class="content__wrap-item">
        <div class="item__img mini__slide-5">
            <div class="mini__slide">
                <img src="/img/photo1.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo2.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo3.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo4.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo5.jpg" alt="">
                <p>ещё 3 фото</p>
            </div>
            <div class="count__photo">
                <img src="/img/camera.svg" alt="">
                <div class="count__photo-num">8</div>
            </div>
        </div>
        <div class="item__content">
            <a href="#" class="item__content-name"><?= $value['title'] ?></a>
            <p class="item__content-city"><?= $value['city'] ?></p>
            <p class="item__content-price"><?
                            if (strlen($value['price']) > 3) {
                                echo substr_replace($value['price'], '.', -3, 0);
                            }
                            else {
                                echo $value['price'];
                            }
                ?> руб.</p>
            <p class="item__content-category"><?= $value['category']?></p>
        </div>

    </div>

<? endforeach ?>

<? $this->params['url'] = $url ?>

<?
?>