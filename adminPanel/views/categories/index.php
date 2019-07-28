<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Url;


    /* @var $this yii\web\View */
    /* @var $searchModel backend\models\crud\CategoriesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $categories backend\models\Categories */
    /* @var $filters backend\models\Filters */

    $this->title = 'Категории';

?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'enablePushState' => false
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key) use ($categories) {
                    $fullName = $model->getAttribute('fullUrl');
                    if (!$fullName) {
                        $fullName = $model->getAttribute('name');
                    }
                    else {
                        $fullName = substr($fullName, 1);
                        $pattern = '/\//';
                        $replace = ' / ';
                        $fullName = preg_replace($pattern, $replace, $fullName);
                        $pattern = '/-/';
                        $replace = ' ';
                        $fullName = preg_replace($pattern, $replace, $fullName);
                    }
                    return Html::a(Html::encode($fullName), Url::to(['view?id=' . $key]), ['title' => Html::encode('Просмотр категории - ' . $model->getAttribute('name'))]);
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
