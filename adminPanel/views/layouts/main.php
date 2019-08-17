<?php

    /**
     * @var $this yii\web\View
     */

    use adminPanel\assets\AppAsset;
    AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?= $this->render('left') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>