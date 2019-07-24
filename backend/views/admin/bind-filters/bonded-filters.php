<?php

    $count = 1;
?>

<?php foreach ($bondedFilters as $key => $filter): ?>

    <tr>
        <th>
            <div class="input-group">
                <span class="input-group-addon"><?= $count++ ?></span>
                <input type="text" class="form-control" value="<?= $key ?>">
            </div>
        </th>
        <th>
            <div class="input-group">
                <select class="form-control" id="select-filters-js">
                    <option disabled="disabled" selected="selected">Выбрать фильтр</option>
                    <?php foreach ($filterNames as $filterName): ?>
                        <?php if ($filterName == $filter): ?>
                            <option selected="selected"><?= $filterName ?></option>
                        <?php else: ?>
                            <option><?= $filterName ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
                <span class="input-group-addon" title="Удалить пункт">
                        <i class="glyphicon glyphicon-remove-circle text-danger"></i>
                    </span>
            </div>
        </th>
    </tr>

<?php endforeach ?>