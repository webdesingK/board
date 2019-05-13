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
        <div id="menu-btn">категории</div>
        <div id="city-btn">Город</div>
        <nav class="menu none">
            <div id='menu-close'>☒</div>
            <?= Categories::createTreeFrontend() ?>
        </nav>
        <nav class="city none">
            <div id='city-close'>☒</div>
            <div class="city__wrap">
                <ul class="city__first">
                    <li><a href="#">ЛНР</a>
                        <ul class="city__second">
                            <li><a href="#">Луганск</a></li>
                            <li><a href="#">Луганск</a>
                                <ul class="city__thrid">
                                    <li><a href="#">восточный квартол</a></li>
                                    <li><a href="#">восточный квартол</a></li>
                                    <li><a href="#">восточный квартол</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="city__first">
                    <li><a href="#">ДНР</a>
                        <ul class="city__second">
                            <li><a href="#">Донецк</a>
                                <ul class="city__thrid">
                                    <li><a href="#">южный квартол</a></li>
                                    <li><a href="#">южный квартол</a></li>
                                    <li><a href="#">южный квартол</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Донецк</a></li>
                            <li><a href="#">Донецк</a></li>
                            <li><a href="#">Донецк</a></li>
                            <li><a href="#">Донецк</a></li>
                            <li><a href="#">Донецк</a></li>
                            <li><a href="#">Донецк</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

</header>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
