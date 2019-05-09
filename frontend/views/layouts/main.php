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
	
		<nav class="menu">
			<div id="menu-btn">категории</div>			
			<?= $this->context->model->createTreeFrontend() ?>
		</nav>
	
</header>
<?= $content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
