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
			
			<ul class="menu__first">
				<li><a href="#">Lorem ipsum.</a>
					<ul class="menu__second">
						<li><a href="#">Lorem ipsum.</a>
							<ul class="menu__third">
								<li><a href="#">Lorem ipsum.</a></li>
								<li><a href="#">Quidem, labore.</a></li>
								<li><a href="#">Cum, eveniet.</a></li>
								<li><a href="#">Modi, ad.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
							</ul>
						</li>
						<li><a href="#">Quidem, labore.</a></li>
						<li><a href="#">Cum, eveniet.</a></li>
						<li><a href="#">Modi, ad.</a><ul class="menu__third">
								<li><a href="#">Lorem ipsum.</a></li>
								<li><a href="#">Quidem, labore.</a></li>
								<li><a href="#">Cum, eveniet.</a></li>
								<li><a href="#">Modi, ad.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
							</ul>
						</li>
						<li><a href="#">Asperiores, soluta.</a><ul class="menu__third">
								<li><a href="#">Lorem ipsum.</a></li>
								<li><a href="#">Quidem, labore.</a></li>
								<li><a href="#">Cum, eveniet.</a></li>
								<li><a href="#">Modi, ad.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
							</ul>
						</li>
						<li><a href="#">Deleniti, minus!</a>
							<ul class="menu__third">
								<li><a href="#">Lorem ipsum.</a></li>
								<li><a href="#">Quidem, labore.</a></li>
								<li><a href="#">Cum, eveniet.</a></li>
								<li><a href="#">Modi, ad.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
								<li><a href="#">Asperiores, soluta.</a></li>
							</ul>
						</li>
						<li><a href="#">Aspernatur, quibusdam.</a></li>
						<li><a href="#">Molestiae, odio.</a></li>
						<li><a href="#">Obcaecati, nisi.</a></li>
						<li><a href="#">Iure, minus.</a></li>
					</ul>
				</li>
				<li><a href="#">Quidem, labore.</a></li>
				<li><a href="#">Cum, eveniet.</a></li>
				<li><a href="#">Modi, ad.</a></li>
				<li><a href="#">Asperiores, soluta.</a></li>
				<li><a href="#">Deleniti, minus!</a></li>
				<li><a href="#">Aspernatur, quibusdam.</a></li>
				<li><a href="#">Molestiae, odio.</a></li>
				<li><a href="#">Obcaecati, nisi.</a></li>
				<li><a href="#">Iure, minus.</a></li>
			</ul>
		</nav>
	
</header>
<?= $content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
