<?php

    /**
     * @var $filters adminPanel\models\filtersManager\Filters
     * @var $categories adminPanel\models\filtersManager\Categories
     * @var $test adminPanel\models\Test
     */

    use yii\bootstrap\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    //    $this->registerJsFile('@web/js/test.js', ['depends' => 'adminPanel\assets\AppAsset']);

?>


<h3 class="text-primary">Создание фильтра</h3>

<hr>

<?php $form = ActiveForm::begin([
    'id' => 'myForm'
]) ?>

<?= $form->field($filters, 'rusName')->textInput(['placeholder' => 'Название фильтра'])->label(false) ?>

<?= $form->field($filters, 'url')->textInput(['placeholder' => 'URL фильтра'])->label(false) ?>

<?= $form->field($filters, 'idParentCategory')->dropDownList(ArrayHelper::map($categories->firstLvl, 'id', 'name'), ['prompt' => 'Выбери категорию'])->label(false) ?>

<p class="text-primary">Содержимое фильтра</p>

<?= Html::button('Добавить поле', ['id' => 'addField', 'class' => 'btn btn-primary']) ?>
<hr>
<?= Html::input('submit', null, 'Создать Фильтр', ['class' => 'btn btn-success',]) ?>

<?php ActiveForm::end() ?>
