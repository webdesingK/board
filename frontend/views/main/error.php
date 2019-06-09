<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?= nl2br(Html::encode($message)) ?>
    </div>

</div>
