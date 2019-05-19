<?php


namespace backend\models;

class Categories extends \common\models\categories\Categories {

    /**
     * @var null
     *
     * В это свойство будет попадать массив $post при ajax запросе
     */

    public $postData = null;

    public function createArray() {

        $allCategories = self::find()->select(['id', 'parent_id', 'depth', 'name', 'active'])->orderBy('lft ASC')->asArray()->all();

        foreach ($allCategories as $k => $v) {
            $class = null;
            if ($v['id'] != 1) $class = 'none';
            $allCategories[$k]['class'] = $class;

            if ($v['active']) {
                $allCategories[$k]['checkbox'] = [
                    'checked' => true,
                    'title' => 'Деактивировать?',
                    'data-active' => 1
                ];
            }
            else {
                $allCategories[$k]['checkbox'] = [
                    'checked' => false,
                    'title' => 'Активировать?',
                    'data-active' => 0
                ];
            }
        }

        return $allCategories;

//        $allCategories = self::find()->select(['id', 'parent_id', 'depth', 'name'])->where(['active' => 1])->andWhere('depth>0')->orderBy('lft ASC')->asArray()->all();
//
//        $lvl = 1;
//
//        echo Html::beginTag('ul', ['class' => 'first']);
//
//        foreach ($allCategories as $k => $v) {
//
//            if ($v['depth'] == $lvl) {
//                if ($k > 0) {
//                    echo Html::endTag('li') . "\n";
//                }
//            }
//            elseif ($v['depth'] > $lvl) {
//                if ($v['depth'] == 2) {
//                    echo Html::beginTag('ul', ['class' => 'second']) . "\n";
//                }
//                elseif ($v['depth'] == 3) {
//                    echo Html::beginTag('ul', ['class' => 'third']) . "\n";
//                }
//            }
//            else {
//                echo Html::endTag('li') . "\n";
//                for ($i = $lvl - $v['depth']; $i; $i--) {
//                    echo Html::endTag('ul') . "\n";
//                    echo Html::endTag('li') . "\n";
//                }
//            }
//
//            echo Html::beginTag('li');
//            echo $v['name'];
//
//            $lvl = $v['depth'];
//
//        }
//        for ($i = $lvl; $i; $i--) {
//            echo Html::endTag('li') . "\n";
//            echo Html::endTag('ul') . "\n";
//        }
//        echo Html::endTag('ul');

    }

}