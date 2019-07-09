<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel backend\models\crud\CitiesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Cities';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'name',
            'url',
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => false,
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
