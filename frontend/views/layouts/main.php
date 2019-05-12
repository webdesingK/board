<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\models\categories\Categories;

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
        <nav class="menu">
            <div id="menu-btn">категории</div>
            <?= Categories::createTreeFrontend() ?>
        </nav>
    </div>

</header>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
