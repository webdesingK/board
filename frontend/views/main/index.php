<?php

?>


    <div class="content__wrap-header">
        <p>Всего объявлений - <?= count($adsData) ?></p>
        <div class="view">
            <div id="view-line">▤</div>
            <div id="view-square">▦</div>
        </div>
    </div>

<?php foreach ($adsData as $key => $value): ?>

    <div class="content__wrap-item">
        <div class="item__photo">
            <div class="item__photo-block">
                <img src="/img/photo1.jpg" alt="photo1">
            </div>
            <div class="item__photo-block">
                <img src="/img/photo2.jpg" alt="photo2">
            </div>
            <div class="item__photo-block">
                <img src="/img/photo3.jpg" alt="photo3">
            </div>
            <div class="item__photo-block">
                <img src="/img/photo4.jpg" alt="photo4">
            </div>
            <div class="item__photo-block">
                <img src="/img/photo5.jpg" alt="photo5">
            </div>
        </div>
        <div class="item__content">
            <a href="#" class="item__content-name"><?= $value['title'] ?></a>
            <p class="item__content-city"><?= $value['city'] ?></p>
            <p class="item__content-price"><?= $value['price'] ?> руб.</p>
            <p class="item__content-category"><?= $value['category'] ?></p>
        </div>
        <!-- <div class="item__img mini__slide-5">
            <div class="mini__slide">
                <div class="hover"></div>
                <img class="img" src="/img/photo1.jpg" alt="sdsd">
            </div>
            <div class="mini__slide">
                <div class="hover"></div>
                <img class="img" src="/img/photo2.jpg" alt="sdds">
            </div>
            <div class="mini__slide">
<<<<<<< HEAD
                <div class="hover"></div>
                <img class="img" src="/img/photo5.jpg" alt="sdsd">
            </div>
            <div class="mini__slide">
                <div class="hover"></div>
                <img class="img" src="/img/photo3.jpg" alt="sdsd">
            </div>
            <div class="mini__slide">
                <div class="hover"></div>
                <img class="img" src="/img/photo4.jpg" alt="sdsd">
=======
                <img src="/img/photo3.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo4.jpg" alt="">
            </div>
            <div class="mini__slide">
                <img src="/img/photo5.jpg" alt="">
>>>>>>> backend
                <p>ещё 3 фото</p>
            </div>
            <div class="count__photo">
                <img class="img" src="/img/camera.svg" alt="sdsd">
                <div class="count__photo-num">8</div>
            </div>
<<<<<<< HEAD
        </div> -->
        =======
    </div>
    <div class="item__content">
        <a href="#" class="item__content-name"><?= $value['title'] ?></a>
        <p class="item__content-city"><?= $value['city'] ?></p>
        <p class="item__content-price"><?php
                if (strlen($value['price']) > 3) {
                    echo substr_replace($value['price'], '.', -3, 0);
                }
                else {
                    echo $value['price'];
                }
            ?> руб.</p>
        <p class="item__content-category"><?= $value['category'] ?></p>
    </div>

    >>>>>>> backend
    </div>

<?php endforeach ?>

<?php $this->params['url'] = $url ?>

<?
?>