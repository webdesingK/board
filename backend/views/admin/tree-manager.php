<?
$this->title = 'Манагер меню';

use common\models\Categories;

?>

<div class="category">

    <div class="category__main">
        <?= Categories::createTree($arrId = null, $lastId = null) ?>
    </div>

</div>


<!-- /.category -->