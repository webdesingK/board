<?
/**
 * @var $arrDeactivatedId array
 */

$this->title = 'Манагер меню';

use common\models\Categories;
//dump(Categories::changeActivate(2, 1));
?>

<div class="category">

    <div class="category__main">
        <?= Categories::createTree($openedIds = null, $lastId = null) ?>
    </div>

</div>


<!-- /.category -->