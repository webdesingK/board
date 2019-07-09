<?php

use yii\helpers\Html;
use backend\assets\AdminAsset;

AdminAsset::register($this);


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
<ul class="menu">
    <li><a href="/">Главная frontend</a></li>
    <li><a href="/админка">Главная backend</a></li>
    <hr>
    <li><a href="/админка/мененджер-категорий">Менеджер категорий</a></li>
    <li><a href="/админка/мененджер-городов">Менеджер городов</a></li>
    <li><a href="/админка/создание-фильтров">Создание фильтров</a></li>
    <li><a href="/админка/редактирование-фильтров">Редактирование фильтров</a></li>
    <li><a href="/админка/привязка-фильтров">Привязка фильтров</a></li>
    <li><a href="/админка/crud-cities">CRUD-Cities</a></li>
    <li><a href="/админка/crud-categories">CRUD-Categories</a></li>
    <li><a href="/админка/gii">GII</a></li>
</ul>

    <?= $content ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
