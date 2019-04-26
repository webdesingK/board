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
        <?= Categories::createTree($arrOpenedId = null, $arrDeactivatedId, $lastId = null) ?>
    </div>

</div>


<!-- /.category -->