<?php

    $count = 1;
?>

<?php foreach ($bondedFilters as $key => $filter): ?>

    <div class="input-group">
        <span class="input-group-addon "><?= $count++ ?></span>
        <select class="form-control select-filter">
            <option disabled="disabled">Выберите фитьтр</option>
            <?php foreach ($allFilters as $oneFilter): ?>
                <?php if ($filter['rusName'] == $oneFilter['rusName']): ?>
                    <option value="<?= $oneFilter['id'] ?>" selected="selected"><?= $oneFilter['rusName'] ?></option>
                <?php else: ?>
                    <option value="<?= $oneFilter['id'] ?>"><?= $oneFilter['rusName'] ?></option>
                <?php endif ?>
            <?php endforeach ?>
        </select>
        <span class="input-group-addon" title="Удалить пункт">
        <i class="glyphicon glyphicon-remove-circle text-danger"></i>
    </span>
    </div>

<?php endforeach ?>