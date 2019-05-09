<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\models\Categories;

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
    <a href="/">Главная</a>
    <a href="http://localhost:3000/admin">localhost-admin</a>
    <a href="/admin">board-admin</a>
    <nav class="menu">
        <div id="menu-btn">категории</div>
        <?= Categories::createTreeFrontend() ?>
    </nav>

</header>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
