<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */

    $this->title = Yii::$app->params['serverErrorMessage'];
?>
<section class="content">

    <div class="error-page">
        <h1><i class="fa fa-warning text-yellow"></i></h1>
        <?= Html::encode(Yii::$app->params['serverErrorMessage']) ?>
    </div>

</section>
