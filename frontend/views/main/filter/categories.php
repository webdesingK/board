<?

use yii\helpers\Html;
use frontend\models\Categories;

$children = Categories::getChildrenByName($url['category']['name']);
$depth = Categories::getDepthByName($url['category']['name']);
$parentId = Categories::getParentIdByName($url['category']['name']);
$siblings = Categories::getSiblingNodesByParentId($parentId);
$parent = Categories::getParentByName($url['category']['name'], 1);

echo Html::beginTag('div', ['class' => 'content__filter-category']);
echo Html::tag('p', 'Все объявления категории' . Html::tag('span', '>'));
echo Html::beginTag('ul', ['id' => 'filter-category', 'class' => 'none']);

if (!empty($children)) {
    echo Html::tag('li', Html::a('Все объявления категории', '/' . $url['city']['url'] . '/' . $url['category']['url']));
    foreach ($children as $child) {
        $link = Html::a($child['name'], '/' . $url['city']['url'] . '/' . $child['url']);
        echo Html::tag('li', $link);
    }
}
else {
    echo Html::tag('li', Html::a('Все объявления категории', '/' . $url['city']['url'] . '/' . $parent[0]['url']));
    foreach ($siblings as $sibling) {
        if ($sibling['name'] == $url['category']['name']) {
            echo Html::tag('li', Html::a($url['category']['name'] . '*', '/' . $url['city']['url'] . '/' . $url['category']['url']));
            continue;
        }
        $link = Html::a($sibling['name'], '/' . $url['city']['url'] . '/' . $sibling['url']);
        echo Html::tag('li', $link);
    }
}


echo Html::endTag('ul');

echo Html::endTag('div');
