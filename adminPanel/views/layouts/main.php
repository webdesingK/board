<?php

    /**
     * @var $this yii\web\View
     * @var $content string
     */

    use adminPanel\assets\AppAsset;
    use yii\helpers\Html;

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
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
        <style>
            .fa-angle-down {
                transition: all 0.3s;
            }

            .rotate {
                transform: rotateZ(-180deg);
            }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="container-fluid">
        <div class="row">

            <div class="col-auto vh-100 p-0 shadow">
                <?= $this->render('left') ?>
            </div>

            <div class="col p-0">
                <?= $this->render('head') ?>

                <?= $this->render('center', [
                    'content' => $content
                ]) ?>
            </div>

        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>