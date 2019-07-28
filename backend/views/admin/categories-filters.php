<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel backend\models\crud\CategoriesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $categories backend\models\Categories */
    /* @var $categoriesFilters backend\models\categoriesFilters\Categories */
    /* @var $filters backend\models\Filters */

    $this->title = 'Привязка категории-фильтры';
?>
<?php

    $categoriesIds = $categoriesFilters::getIds();
    $filtersData = $filters->getFilterData(['id', 'rusName', 'url']);
    $filtersCategoriesIds = $filters->filtersCategoriesIds;
    $bondedFilters = [];

    foreach ($categoriesIds as $categoryId) {
        foreach ($filtersCategoriesIds as $filtersCategoriesId) {
            if ($filtersCategoriesId['idCategory'] == $categoryId) {
                foreach ($filtersData as $filtersDatum) {
                    if ($filtersDatum['id'] == $filtersCategoriesId['idFilter']) {
                        $bondedFilters[$categoryId][] = $filtersDatum;
                    }
                }
            }
        }
    }

?>
<div class="categories-index">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php Pjax::begin([
        'enablePushState' => false
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'attribute' => 'name',
                'content' => function ($model, $key) use ($categories) {
                    $fullName = $model->getAttribute('fullUrl');
                    $fullName = substr($fullName, 1);
                    $pattern = '/\//';
                    $replace = ' / ';
                    $fullName = preg_replace($pattern, $replace, $fullName);
                    $pattern = '/-/';
                    $replace = ' ';
                    $fullName = preg_replace($pattern, $replace, $fullName);
                    return $fullName;
                }
            ],
            [
                'label' => 'Привязанные фильтры',
                'content' => function ($model, $key) use ($bondedFilters) {
                    $str = '';
                    if (!empty($bondedFilters) && key_exists($key, $bondedFilters)) {
                        $count = count($bondedFilters[$key]);
                        foreach ($bondedFilters[$key] as $key => $bondedFilter) {
                            if ($count > 1) {
                                $str .= ++$key . ') Название: ' . $bondedFilter['rusName'] . ';';
                            }
                            else {
                                $str .= 'Название: ' . $bondedFilter['rusName'] . ';';
                            }
                            $str .= ' URL: ' . $bondedFilter['url'] . '<br>';
                        }
                    }
                    return $str;
                }
            ],
        ]
    ]) ?>

    <?php Pjax::end() ?>

</div>
