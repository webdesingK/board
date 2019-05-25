<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="header">

    <div class="header__wrap">
        <a href="/">Главная</a>
        <a href="http://localhost:3000/admin">localhost-admin</a>
        <a href="/admin">board-admin</a>
        <div class="auth">
            <p id="auth">Вход</p>
            <!-- <div id="auth-user">⛑</div> -->
            <div class="auth__user none">
                <a href="#">Личный кабинет</a>
                <a href="#">Выйти</a>
            </div>
            <div id="sign-in" class="none">
                <p>Вход</p>
                <form>
                    <input type="text" placeholder="Электронная почта">
                    <input type="text" placeholder="Пароль">
                    <button>Войти</button>
                </form>
                <a href="#">BK</a>
                <a href="#">ОД</a>
                <hr>
                <span>Регистрация</span>
            </div>
            <div id="sign-up" class="none">
                <p>Регистрация</p>
                <form>
                    <input type="text" placeholder="Электронная почта">
                    <input type="text" placeholder="Пароль">
                    <input type="text" placeholder="Подтверждение пароля">
                    <button>Зарегистрироваться</button>
                </form>
                <a href="#">BK</a>
                <a href="#">ОД</a>
                <hr>
                <span>Вход</span>
            </div>
        </div>
        <div id="menu-btn">категории</div>
        <div id="city-btn">Город</div>
        <nav class="menu none">
            <div id='menu-close'>☒</div>
            <?= $this->render('//site/categories-menu') ?>
        </nav>
        <nav class="city none">
            <div id='city-close'>☒</div>
            <div class="city__wrap">
                <?= $this->render('//site/cities-menu') ?>
            </div>
        </nav>
    </div>

</header>
<div class="load"></div>
<div class="load-opacity none"></div>
<!-- <div class="btn-load"></div> -->

<!-- content -->
<div class="content">
    <div class="content__filter">
        <div class="content__filter-category">
            <p>lorem <span>></span></p>
            <ul id="filter-category" class="none">
                <li><a href="#">Lorem ipsum.</a></li>
                <li><a href="#">Iste, nihil.</a></li>
                <li><a href="#">Autem, quisquam.</a></li>
                <li><a href="#">Doloremque, ex.</a></li>
                <li><a href="#">Deserunt, laborum?</a></li>
                <li><a href="#">Reiciendis, animi!</a></li>
                <li><a href="#">Expedita, consequuntur.</a></li>
                <li><a href="#">Quis, incidunt.</a></li>
                <li><a href="#">Neque, itaque.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
                <li><a href="#">Porro, nulla.</a></li>
            </ul>
        </div>
        <div class="content__filter-type">
            <p>Тип <span>></span></p>
            <ul id="filter-type" class="none">
                <li>
                    <label>text</label>
                    <input type="checkbox" data-id="1">
                </li>
                <li>
                    <label>text</label>
                    <input type="checkbox" data-id="2">
                </li>
                <li>
                    <label>text</label>
                    <input type="checkbox" data-id="3">
                </li>
                <li>
                    <label>text</label>
                    <input type="checkbox" data-id="4">
                </li>
            </ul>
        </div>
        <div class="content__filter-price">
            <p>Цена</p>
            <div class="price-filter">
                <input type="text" placeholder="от">
                <input type="text" placeholder="до">
            </div>
        </div>
        <div class="content__filter-btn">Применить</div>
    </div>
    <div class="content__wrap">
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
