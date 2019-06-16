<?

use yii\helpers\Html;
use frontend\models\Categories;

echo Html::beginTag('div', ['class' => 'content__filter-category multitype-filter']);
echo Html::beginTag('ul');

if (!isset($url['category'])) {

    $children = Categories::getChildrenById(1, 1);

    echo Html::tag('li', Html::a('Все категории', '/' . $url['city']['url']), ['class' => 'active__filter-category']);

    foreach ($children as $item) {
        echo Html::tag('li', Html::a($item['name'], '/' . $url['city']['url'] . '/' . $item['url']));
    }

} else {

    $children = Categories::getChildrenById($url['category']['id'], 1);
    $parent = Categories::getParentById($url['category']['id']);

    echo Html::tag('li', Html::a('Все категории', '/' . $url['city']['url']));

    if (!empty($children)) {

        if ($parent['depth'] > 0) {
            echo Html::tag('li', Html::a($parent['name'], '/' . $url['city']['url'] . '/' . $parent['url']));
        }

        echo Html::tag('li', Html::a($url['category']['name'], '/' . $url['city']['url'] . '/' . $url['category']['url']), ['class' => 'active__filter-category']);

        foreach ($children as $child) {
            echo Html::tag('li', Html::a($child['name'], '/' . $url['city']['url'] . '/' . $child['url']));
        }

    } else {

        $siblings = Categories::getSiblingNodesByParentId($url['category']['parent_id']);

        echo Html::tag('li', Html::a($parent['name'], '/' . $url['city']['url'] . '/' . $parent['url']));

        foreach ($siblings as $sibling) {
            if ($sibling['name'] == $url['category']['name']) {
                echo Html::tag('li', Html::a($url['category']['name'], '/' . $url['city']['url'] . '/' . $url['category']['url']), ['class' => 'active__filter-category']);
                continue;
            }
            echo Html::tag('li', Html::a($sibling['name'], '/' . $url['city']['url'] . '/' . $sibling['url']));
        }

    }

}


echo Html::endTag('ul');

echo Html::endTag('div');
