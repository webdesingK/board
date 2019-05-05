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
			<li><a href="/admin">Главная</a></li>
            <hr>
			<li><a href="/admin/categories-manager">Менеджер категорий</a></li>
			<li><a href="/admin/cities-manager">Менеджер городов</a></li>
		</ul>

<? \yii\widgets\Pjax::begin([
        'linkSelector' => '.menu a'
])?>

    <?= $content ?>

<? \yii\widgets\Pjax::end()?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
