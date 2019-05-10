<?
/**
 * @var $model
 */

$this->title = 'Манагер категорий';

?>

<div class="category">

    <div class="category__main">
        <?= $model->createTree($openedIds = null, $lastId = null) ?>
    </div>

</div>


<!-- /.category -->