<?php

    /**
     * @var $filters adminPanel\models\filtersManager\Filters
     * @var $session yii\web\Session
     */

    use yii\bootstrap4\ActiveForm;
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

    echo $form->field($filters, 'title[]', [
        'options' => [
            'class' => 'form-group field-title-' . $count
        ],
        'inputOptions' => [
            'id' => 'title-' . $count,
            'placeholder' => 'Содержимое фильтра'
        ],
        'inputTemplate' => '
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">' . $count . '</span>
                            </div>
                            {input}
                            <div class="input-group-append">
                                <span class="input-group-text bg-danger" title="Удалить пункт">
                                    <i class="fas fa-times text-white"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    '
    ])->label(false)->error(false);

?>
    <script>
        $('#myForm').yiiActiveForm('add', {
            id: 'title-<?= $count ?>',
            name: 'title[]',
            container: '.field-title-<?= $count ?>',
            input: '#title-<?= $count ?>',
            error: '.invalid-feedback',
            validate: function (attribute, value, messages, deferred, form) {
                yii.validation.required(value, messages, {message: 'Пусто Вася ... '});
                // yii.validation.number(value, messages, {pattern:/^\s*[+-]?\d+\s*$/, message: 'Не число, Вася ... '});
            }
        });
    </script>
<?php
    $session->set('idTitle', ++$count);
?>