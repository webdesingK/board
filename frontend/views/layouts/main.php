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
<!--        <div class="filter-js multitype-filter">-->
<!--            <p>Размер<span class="arrow-open">➤</span></p>-->
<!--            <ul class="none">-->
<!--                <li>-->
<!--                    <label for="womens-40_42">40-42(XS)</label>-->
<!--                    <input type="checkbox" id="womens-40_42">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-42_44">42-44(S)</label>-->
<!--                    <input type="checkbox" id="womens-42_44">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-44_46">44-46(M)</label>-->
<!--                    <input type="checkbox" id="womens-44_46">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-46_48">46-48(L)</label>-->
<!--                    <input type="checkbox" id="womens-46_48">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-48_50">48-50(XL)</label>-->
<!--                    <input type="checkbox" id="womens-48_50">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-50_52">50-52(XXL)</label>-->
<!--                    <input type="checkbox" id="womens-50_52">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-52_54">52-54(XXXL) и больше</label>-->
<!--                    <input type="checkbox" id="womens-52_54">-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="filter-js multitype-filter">-->
<!--            <p>Размер<span class="arrow-open">➤</span></p>-->
<!--            <ul class="none">-->
<!--                <li>-->
<!--                    <label for="womens-40_42">40-42(XS)</label>-->
<!--                    <input type="checkbox" id="womens-40_42">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-42_44">42-44(S)</label>-->
<!--                    <input type="checkbox" id="womens-42_44">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-44_46">44-46(M)</label>-->
<!--                    <input type="checkbox" id="womens-44_46">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-46_48">46-48(L)</label>-->
<!--                    <input type="checkbox" id="womens-46_48">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-48_50">48-50(XL)</label>-->
<!--                    <input type="checkbox" id="womens-48_50">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-50_52">50-52(XXL)</label>-->
<!--                    <input type="checkbox" id="womens-50_52">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-52_54">52-54(XXXL) и больше</label>-->
<!--                    <input type="checkbox" id="womens-52_54">-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="filter-js multitype-filter">-->
<!--            <p>Размер<span class="arrow-open">➤</span></p>-->
<!--            <ul class="none">-->
<!--                <li>-->
<!--                    <label for="womens-40_42">40-42(XS)</label>-->
<!--                    <input type="checkbox" id="womens-40_42">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-42_44">42-44(S)</label>-->
<!--                    <input type="checkbox" id="womens-42_44">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-44_46">44-46(M)</label>-->
<!--                    <input type="checkbox" id="womens-44_46">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-46_48">46-48(L)</label>-->
<!--                    <input type="checkbox" id="womens-46_48">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-48_50">48-50(XL)</label>-->
<!--                    <input type="checkbox" id="womens-48_50">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-50_52">50-52(XXL)</label>-->
<!--                    <input type="checkbox" id="womens-50_52">-->
<!--                </li>-->
<!--                <li>-->
<!--                    <label for="womens-52_54">52-54(XXXL) и больше</label>-->
<!--                    <input type="checkbox" id="womens-52_54">-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
    <div class="content__wrap">
        <?php dump($this->params['url']) ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
