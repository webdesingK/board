<?

use yii\helpers\Html;
use frontend\models\Categories;

echo Html::beginTag('div', ['class' => 'content__filter-category multitype-filter']);
echo Html::beginTag('ul');

if (!isset($url['categories'])) {

    $childrenOfRoot = Categories::getChildrenOfRoot();

    if ($url['city']['current']['url'] == $url['city']['default']['url']) {
        $allCategoriesUrl = '/';
    }
    else {
        $allCategoriesUrl = '/' . $url['city']['current']['url'];
    }

    echo Html::tag('li', Html::a('Все категории', $allCategoriesUrl), ['class' => 'active__filter-category']);

    foreach ($childrenOfRoot as $child) {
        echo Html::tag('li', Html::a($child->name, '/' . $url['city']['current']['url'] . $child->fullUrl), ['class' => 'none lvl-' . $child->depth]);
    }

}
else {

    $children = Categories::getChildrenByNode($url['currentCategory']);

    if ($url['currentCategory']->depth == 2) {
        $parent = $url['categories']['categoryFirstLvl'];
    }
    elseif ($url['currentCategory']->depth == 3) {
        $parent = $url['categories']['categorySecondLvl'];
    }
    else {
        $parent = null;
    }

    if ($url['city']['current']['url'] == $url['city']['default']['url']) {
        $allCategoriesUrl = '/';
    }
    else {
        $allCategoriesUrl = '/' . $url['city']['current']['url'];
    }

    echo Html::tag('li', Html::a('Все категории', $allCategoriesUrl), ['class' => 'none']);

    if (!empty($children)) {

        if ($parent && $parent->depth > 0) {
            echo Html::tag('li', Html::a($parent->name, '/' . $url['city']['current']['url'] . $parent->fullUrl), ['class' => 'none lvl-' . $parent->depth]);
        }

        echo Html::tag('li', Html::a($url['currentCategory']->name, '/' . $url['city']['current']['url'] . $url['currentCategory']->fullUrl), ['class' => 'active__filter-category', 'data-lvl' => $url['currentCategory']->depth]);

        foreach ($children as $child) {
            echo Html::tag('li', Html::a($child['name'], '/' . $url['city']['current']['url'] . $child['fullUrl']), ['class' => 'none lvl-' . $child['depth']]);
        }

    }
    else {

        if ($parent) {

            $siblings = Categories::getSiblingNodesByParent($parent);

            if ($parent['depth'] > 0) {
                echo Html::tag('li', Html::a($parent->name, '/' . $url['city']['current']['url'] . $parent->fullUrl), ['class' => 'none lvl-1']);
            }

            foreach ($siblings as $sibling) {
                if ($sibling['name'] == $url['currentCategory']->name) {
                    echo Html::tag('li', Html::a($sibling['name'], '/' . $url['city']['current']['url'] . $sibling['fullUrl']), ['class' => 'active__filter-category', 'data-lvl' => 2]);
                    continue;
                }
                echo Html::tag('li', Html::a($sibling['name'], '/' . $url['city']['current']['url'] . $sibling['fullUrl']), ['class' => 'none lvl-2']);
            }
        }

    }

}


echo Html::endTag('ul');

echo Html::endTag('div');
