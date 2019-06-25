<?

use yii\helpers\Html;
use frontend\models\Categories;
use console\components\Translate;

echo Html::beginTag('div', ['class' => 'content__filter-type multitype-filter']);
echo Html::tag('p', 'Тип' . Html::tag('span', '➤', ['class' => 'arrow-open']));
echo Html::beginTag('ul');

$types = Categories::getTypes($url['category']['id']);

foreach ($types as $type) {
    $label = Html::tag('label', $type['name'], ['for' => Translate::translateRuToEn($type['name'])]);
    $checkbox = Html::checkbox(null, false, ['id' => Translate::translateRuToEn($type['name'])]);
    echo Html::tag('li', $label . $checkbox);
}

echo Html::endTag('ul');
echo Html::endTag('div');