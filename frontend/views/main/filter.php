<?php

    use frontend\models\Categories;
    use yii\helpers\Html;

    echo Html::beginTag('div', ['class' => 'content__filter', 'id' => 'filter']);

// ---------------------------- Категории начало

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

        $children = [];

        if ((($url['currentCategory']->rgt - $url['currentCategory']->lft) > 1) && $url['currentCategory']->depth < 3) {
            $children = Categories::getChildrenByNode($url['currentCategory']);
        }

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

// --------------------------- Категории конец

// --------------------------- Типы начало

    if (isset($url['currentCategory']) && $url['currentCategory']->depth == 3) {
        if (($url['currentCategory']->rgt - $url['currentCategory']->lft) > 1) {
            echo Html::beginTag('div', ['class' => 'filter-js multitype-filter']);
            echo Html::tag('p', 'Тип' . Html::tag('span', '➤', ['class' => 'arrow-open']));
            echo Html::beginTag('ul', ['class' => 'none']);

            $types = Categories::getTypes($url['currentCategory']);

            foreach ($types as $type) {                         // Возможно слабое место (замедление работы)
                $randNum = rand(0, 25);
                $md5 = md5($type['name']);
                $id = substr($md5, $randNum, 5);
                $label = Html::tag('label', $type['name'], ['for' => $id]);
                $checkbox = Html::checkbox(null, false, ['id' => $id, 'value' => null]);
                echo Html::tag('li', $label . $checkbox);
            }

            echo Html::endTag('ul');
            echo Html::endTag('div');
        }
    }

// --------------------------- Типы конец

// --------------------------- Цена начало

    if (isset($url['currentCategory'])) {
        echo Html::beginTag('div', ['class' => 'content__filter-price multitype-filter']);
        echo Html::tag('p', 'Цена');
        echo Html::beginTag('div', ['class' => 'price-filter']);
        echo Html::input('text', null, null, ['id' => 'price__filter-min', 'placeholder' => 'от']);
        echo Html::input('text', null, null, ['id' => 'price__filter-max', 'placeholder' => 'до', 'data-max' => 7777777]);
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

// --------------------------- Цена конец
    echo '<div class="filter-js multitype-filter">
                <p>Размер<span class="arow-open">➤</span></p>
                <ul class="none">
                    <li>
                        <label for="womens-40_42">40-42(XS)</label>
                        <input type="checkbox" id="womens-40_42">
                    </li>
                    <li>
                        <label for="womens-42_44">42-44(S)</label>
                        <input type="checkbox" id="womens-42_44">
                    </li>
                    <li>
                        <label for="womens-44_46">44-46(M)</label>
                        <input type="checkbox" id="womens-44_46">
                    </li>
                    <li>
                        <label for="womens-46_48">46-48(L)</label>
                        <input type="checkbox" id="womens-46_48">
                    </li>
                    <li>
                        <label for="womens-48_50">48-50(XL)</label>
                        <input type="checkbox" id="womens-48_50">
                    </li>
                    <li>
                        <label for="womens-50_52">50-52(XXL)</label>
                        <input type="checkbox" id="womens-50_52">
                    </li>
                    <li>
                        <label for="womens-52_54">52-54(XXXL) и больше</label>
                        <input type="checkbox" id="womens-52_54">
                    </li>
                </ul>
            </div>';
    echo Html::tag('div', 'Применить', ['class' => 'content__filter-btn multitype-filter']);

    echo Html::endTag('div');