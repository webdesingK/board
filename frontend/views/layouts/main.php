<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\widgets\Breadcrumbs;
use yii2tech\filedb\Query;

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
        <a href="http://localhost:3000/админка">localhost-admin</a>
        <a href="/админка">board-admin</a>
        <a href="/memcached" target="_blank">memcached</a>
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
        <div id="city-btn">
            <?
            if (isset($this->params['url']['city']) && $this->params['url']['city']['name'] != 'Все города') {
                echo $this->params['url']['city']['name'];
                echo "\t";
                $linkUrl = empty($this->params['url']['category']['url']) ? '/' : '/Все-города/' . $this->params['url']['category']['url'];
                echo Html::a('X', $linkUrl);
            }
            else {
                echo 'Местоположение';
            }
            ?>
        </div>

        <nav class="menu">
            <div id='menu-close'>☒</div>
            <?= $this->render('//main/menu/categories', ['url' => $this->params['url']]) ?>
        </nav>
        <nav class="city">
            <div id='city-close'>☒</div>
            <div class="city__wrap">
                <?= $this->render('//main/menu/cities', ['url' => $this->params['url']]) ?>
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
        <?= $this->render('//main/filter/categories', ['url' => $this->params['url']]) ?>
        <?
            if (isset($this->params['url']['category']) && $this->params['url']['category']['depth'] == 3) {
                if (($this->params['url']['category']['rgt'] - $this->params['url']['category']['lft']) > 1) {
                    echo $this->render('//main/filter/type', ['url' => $this->params['url']]);
                }
            }

        ?>
        <div class="content__filter-price multitype-filter">
            <p>Цена</p>
            <div class="price-filter">
                <input id="price__filter-min" type="text" placeholder="от">
                <input id="price__filter-max" type="text" placeholder="до">
            </div>
        </div>
        <div class="content__filter-btn multitype-filter">Применить</div>
    </div>
    <div class="content__wrap">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
