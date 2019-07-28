<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\crud\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shortUrl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullUrl')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::a('Просмотр списка', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
