<?php

    /**
     * @var $filters adminPanel\models\filtersManager\Filters
     * @var $session yii\web\Session
     */

    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    $form = new ActiveForm();

    if ($session->has('idTitle')) {
        $count = $session->get('idTitle');
    }
    else {
        $count = 1;
    }

?>

        <?php

//            echo $form->field($filters, 'title[]', ['options' => ['class' => 'col-md-11']])->textInput(['id' => 'title-' . $count, 'placeholder' => 'Содержимое фильтра'])->label(false);
//            echo Html::button('Удалить', ['class' => 'btn btn-danger col-md-1']);

        ?>
        <div class="input-group">
            <span class="input-group-addon"><?= $count ?></span>
            <input type="text" class="form-control" id="title-<?= $count ?>" name="title[]" placeholder="Содержимое фильтра">
            <span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
            <script>
                $('#myForm').yiiActiveForm('add', {
                    container: '.field-title-<?= $count ?>',
                    input: '#<?= 'title-' . $count ?>',
                    validate: function (attribute, value, messages, deferred, form) {
                        yii.validation.required(value, messages, {message: 'Пусто Вася ... '});
                        // yii.validation.number(value, messages, {pattern:/^\s*[+-]?\d+\s*$/, message: 'Не число, Вася ... '});
                    }
                });
            </script>
        </div>
    <br>
<?php

    $session->set('idTitle', ++$count);
?>