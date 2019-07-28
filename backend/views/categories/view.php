<?php

    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use yii\helpers\ArrayHelper;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $model backend\models\crud\Categories */
    /* @var $filters backend\models\Filters */

    $this->title = $model->name;

?>

<div class="categories-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'enablePushState' => false
    ]) ?>
    <p>
        <?= Html::a('Все категории', ['index'], ['class' => 'btn btn-primary']) ?>
        <!--    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'lft',
            'rgt',
            'depth',
            'active',
            'name',
            'shortUrl',
            'fullUrl',
            [
                'label' => 'Привязанные фильтры',
                'format' => 'html',
                'value' => function ($model) use ($filters) {
                    $id = $model->getAttribute('id');
                    $idsFilters = ArrayHelper::getColumn($filters->getFiltersIdsBycategoryId($id), 'idFilter');
                    $str = 'Пусто';

                    if (!empty($idsFilters)) {
                        $filterData = $filters->getFiltersDataByIds($idsFilters);
                        $count = count($filterData);
                        $str = '';
                        foreach ($filterData as $key => $filterDatum) {
                            if ($count > 1) {
                                $str .= ++$key . ') Название: ' . $filterDatum['rusName'] . ';';
                            }
                            else {
                                $str .= ' Название: ' . $filterDatum['rusName'] . ';';

                            }
                            $str .= 'URL: ' . $filterDatum['url'] . '<br>';
                        }
                    }
                    return $str;
                }
            ]
        ],
    ]) ?>

    <?php Pjax::end() ?>

</div>
