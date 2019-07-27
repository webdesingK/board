<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;


    /* @var $this yii\web\View */
    /* @var $searchModel backend\models\crud\CategoriesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $categories backend\models\crud\Categories */
    /* @var $filters backend\models\Filters */

    $this->title = 'Категории';

?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

        $categoriesData = $categories->data;
        $filtersData = $filters->getFilterData(['id', 'rusName', 'url']);
        $filtersCategoriesIds = $filters->filtersCategoriesIds;
        $bondedFilters = [];

        foreach ($categoriesData as $categoryData) {
            foreach ($filtersCategoriesIds as $filtersCategoriesId) {
                if ($filtersCategoriesId['idCategory'] == $categoryData['id']) {
                    foreach ($filtersData as $filtersDatum) {
                        if ($filtersDatum['id'] == $filtersCategoriesId['idFilter']) {
                            $bondedFilters[$categoryData['id']][] = $filtersDatum;
                        }
                    }
                }
            }
        }
    ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'Название категорий',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key) {
                    return Html::a(Html::encode($model->getAttribute('name')), Url::to(['view?id=' . $key]), ['title' => Html::encode('Просмотр категории - ' . $model->getAttribute('name'))]);
                }
            ],
            [
                'label' => 'Привязанные фильтры',
//                'content' => function ($model, $key) use ($filters) {
//                    $str = '';
//
//                    $idsFilters = ArrayHelper::getColumn($filters::getFiltersIdsBycategoryId($key), 'idFilter');
//
//                    if (!empty($idsFilters)) {
//                        $filterData = $filters::getFiltersDataByIds($idsFilters);
//                        $str = '';
//                        $count = 1;
//                        foreach ($filterData as $filterDatum) {
//                            $str .= $count++ . ') Название фильтра: ' . $filterDatum['rusName'] . ';';
//                            $str .= ' URL: ' . $filterDatum['url'] . '<br>';
//                        }
//                    }
//
//                    return $str;
//
//                }
                'content' => function ($model, $key) use ($bondedFilters) {
                    $str = '';
                    if (!empty($bondedFilters) && key_exists($key, $bondedFilters)) {
                        $count = count($bondedFilters[$key]);
                        foreach ($bondedFilters[$key] as $key => $bondedFilter) {
                            if ($count > 1) {
                                $str .= ++$key . ') Название фильтра: ' . $bondedFilter['rusName'] . ';';
                            }
                            else {
                                $str .= 'Название фильтра: ' . $bondedFilter['rusName'] . ';';
                            }
                            $str .= ' URL: ' . $bondedFilter['url'] . '<br>';
                        }
                    }
                    return $str;
                }
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'visibleButtons' => [
//                    'update' => false,
//                    'delete' => false
//                ]
//            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
