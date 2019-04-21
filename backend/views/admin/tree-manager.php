<?
$this->title = 'Манагер меню';

use common\models\Categories;

?>

<div class="category">

    <div class="category__main">
        <?= Categories::createTree() ?>
    </div>

</div>
<!-- /.category -->