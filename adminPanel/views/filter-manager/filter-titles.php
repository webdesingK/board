<?php

    /**
     * @var $titles array
     */

?>

<?php foreach($titles as $key => $title): ?>

<tr>
    <td>
        <div class="input-group">
            <span class="input-group-addon"><?= $key + 1 ?></span>
            <input value="<?= $title['title'] ?>" type="text" class="form-control">
            <span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
        </div>
    </td>
</tr>

<?php endforeach ?>