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
        <a href="http://localhost:3000/admin-panel" target="_blank">localhost-admin-panel</a>
        <a href="/admin-panel" target="_blank">admin-panel</a>
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
                <div class="close-sign">☒</div>
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
                <div class="close-sign">☒</div>
            </div>
        </div>
        <div class="menu-btn">категории</div>
        <div class="city-btn">
            <?php
                if ($this->params['url']['city']['current']['name'] != $this->params['url']['city']['default']['name']) {
                    echo $this->params['url']['city']['current']['name'];
                    echo "\t";
                    $linkUrl = empty($this->params['url']['categories']) ? '/' : '/' . $this->params['url']['city']['default']['url'] . $this->params['url']['currentCategory']->fullUrl;
                    echo Html::a('X', $linkUrl);
                }
                else {
                    echo 'Местоположение';
                }
            ?>
        </div>

        <nav class="menu none">
            <div id='menu-close'>☒</div>
            <?= $this->render('//main/menu/categories', ['url' => $this->params['url']]) ?>
        </nav>
        <nav class="city none">
            <div id='city-close'>☒</div>
            <div class="city__wrap">
                <?= $this->render('//main/menu/cities', ['url' => $this->params['url']]) ?>
            </div>
        </nav>
    </div>
</header>
<div class="load"></div>
<div class="load-opacity none"></div>
<!-- content -->
<div class="content">
    <?= $this->render('//main/filter', ['url' => $this->params['url']]) ?>

    <div class="content__wrap">

        <?php

            $linkMainText = 'Все объявления в ' . $this->params['url']['city']['current']['name'];
            if ($this->params['url']['city']['current']['url'] == $this->params['url']['city']['default']['url']) {
                $linkMainUrl = '/';
            }
            else {
                $linkMainUrl = '/' . $this->params['url']['city']['current']['url'];
            }
            echo Html::a($linkMainText, $linkMainUrl);
            if (isset($this->params['url']['currentCategory'])) {
                $count = count($this->params['url']['categories']);
                $key = 1;
                foreach ($this->params['url']['categories'] as $category) {
                    echo '&nbsp;/&nbsp;';
                    if ($key == $count) {
                        echo Html::a($category->name);
                    }
                    else {
                        echo Html::a($category->name, '/' . $this->params['url']['city']['current']['url'] . $category->fullUrl);
                    }
                    $key++;
                }
            }

        ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
