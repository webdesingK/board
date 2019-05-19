<?php


namespace backend\models;

use yii\helpers\Html;

class Cities extends \common\models\cities\Cities {

    static public function createArray() {

        $all = self::find()->select(['id', 'parent_id', 'depth', 'name'])->orderBy('lft ASC')->asArray()->all();

        $lvl = 1;

        echo Html::beginTag('ul', ['class' => 'menu__first']);

        foreach ($all as $one) {

            if ($one['depth'] == 0) continue;

            if ($one['depth'] == $lvl) {

            }

            $lvl = $one['depth'];

        }

        echo Html::endTag('ul');

    }

}