<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\Html;

class Categories extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'categories';
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
            ],
        ];
    }

    public function transactions() {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find() {
        return new CategoriesQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'parent_id'], 'required'],
            ['name', 'string'],
            ['parent_id', 'integer'],
        ];
    }

    /**
     * @param $parentId int
     * @param $name string
     * @return bool|int
     */

    public function createNode($parentId, $name) {
        $dataForModel = [
            'parent_id' => $parentId,
            'name' => $name
        ];
        if (self::load($dataForModel, '')) {
            $parentNode = self::find()->where(['id' => $parentId])->one();
            if (self::prependTo($parentNode)) {
                return self::getLastId($name);
            }
        }
        return false;
    }

    /**
     * @param $ids array
     * @param $value int
     * @return bool
     */

    public function changeActive($ids, $value) {
        if (self::updateAll(['active' => $value], ['in', 'id', $ids])) {
            return true;
        }
        return false;
    }

    /**
     * @param $id int
     * @param $newName string
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function renameNode($id, $newName) {
        $node = self::find()->where(['id' => $id])->one();
        $node->name = $newName;
        if ($node->update()) {
            return true;
        }
        return false;
    }

    /**
     * @param $id int
     * @param $siblingId int
     * @param $direction string
     * @return bool
     */

    public function moveNode($id, $siblingId, $direction) {
        $node = self::find()->where(['id' => $id])->one();
        $sibling = self::find()->where(['id' => $siblingId])->one();
        $result = false;
        if ($direction == 'up') {
            $result = $node->insertBefore($sibling);
        }
        elseif ($direction == 'down') {
            $result = $node->insertAfter($sibling);
        }
        return $result;
    }

    /**
     * @param $id int
     * @return bool
     */

    public function deleteNode($id) {
        $node = self::find()->where(['id' => $id])->one();
        if ($node->deleteWithChildren()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return integer
     */

    public function getLastId($name) {
        $lastString = self::find('id')->where(['name' => $name])->one();
        return $lastString->id;
    }

    protected function getDeactiveNodes () {
        return self::find()->select(['id'])->where(['active' => 0])->asArray()->all();
    }

    /**
     * @param $openedIds array
     * @param $lastId int
     * @return string|null
     */

    public function createTree($openedIds, $lastId) {

        $allCats = self::find()->orderBy('lft ASC')->all();
        $cats = [];

        foreach ($allCats as $cat) {
            $cats[$cat->parent_id][] = [
                'name' => $cat->name,
                'id' => $cat->id,
            ];
        }

        if (isset($lastId)) array_push($openedIds, $lastId);

        function create($cats, $parentId, $openedIds) {

            if (isset($cats[$parentId]) && is_array($cats[$parentId])) {
                $tree = '';
                $class = null;
                foreach ($cats[$parentId] as $cat) {

                    if (!is_array($openedIds)) {
                        if ($cat['id'] != 1) $class = 'none';
                    }
                    else {
                        if (!in_array($cat['id'], $openedIds)) $class = 'none';
                    }

                    $tree .= '
                        <div class="category__list ' . $class . '" data-id="' . $cat['id'] . '">
                             <div class="category__list-block">
				                <span class="name-category">' . Html::encode($cat['name']) . '</span>
				                <span class="add-category" title="Добавить новую категорию">&plus;</span>
                                <span class="edit-category" title="Редоктирование названия категории">✎</span>
                                <span class="del-category" title="Удалить категорию и подкатегории">✘</span>
                                <input type="checkbox" checked class="checkbox" title="Деактивировать?" data-active="1">
                                <span class="motion__up-category" title="Переместить вверх">➭</span>
                                <span class="motion__down-category" title="Переместить вниз">➭</span>
				                <span class="tabs-category" title="Развернуть">▶</span>
			                </div>
                        ';
                    $tree .= create($cats, $cat['id'], $openedIds);
                    $tree .= '</div>';
                }
            }
            else {
                return null;
            }
            return $tree;
        }

        return create($cats, 0, $openedIds);

    }

    public function createTreeFrontend() {

        $all = self::find()->select(['id', 'parent_id', 'depth', 'active', 'name'])->orderBy('lft ASC')->asArray()->all();

        $menuFirstData = [];
        $menuSecondData = [];
        $menuThirdData = [];

        foreach ($all as $one) {
            if (!$one['active']) continue;
            switch ($one['depth']) {
                case 1:
                    $menuFirstData[] = [
                        'id' => $one['id'],
                        'active' => $one['active'],
                        'name' => $one['name'],
                        'openLi' => '<li><a href="#" data-id="' . $one['id'] . '">' . $one['name'] . '<span>></span></a>',
                    ];
                    break;
                case 2:
                    $menuSecondData[] = [
                        'id' => $one['id'],
                        'parent_id' => $one['parent_id'],
                        'active' => $one['active'],
                        'name' => $one['name'],
                        'openLi' => '<li><a href="#" data-id="' . $one['id'] . '">' . $one['name'] . '</a>',
                    ];
                    break;
                case 3:
                    $menuThirdData[] = [
                        'id' => $one['id'],
                        'parent_id' => $one['parent_id'],
                        'active' => $one['active'],
                        'name' => $one['name'],
                        'li' => '<li><a href="#" data-id="' . $one['id'] . '">' . $one['name'] . '</a></li>',
                    ];
                    break;
            }
        }

        foreach ($menuSecondData as $sk => $sv) {
            foreach ($menuThirdData as $thk => $thv) {
                if ($thv['parent_id'] == $sv['id']) {
                    $menuSecondData[$sk]['openUl'] = '<ul class="menu__third">';
                    $menuSecondData[$sk]['li'][] = $thv;
                    $menuSecondData[$sk]['closeUl'] = '</ul>';
                }
            }
            $menuSecondData[$sk]['closeLi'] = '</li>';
        }

        foreach ($menuFirstData as $fk => $fv) {
            foreach ($menuSecondData as $sk => $sv) {
                if ($sv['parent_id'] == $fv['id']) {
                    $menuFirstData[$fk]['openUl'] = '<ul class="menu__second">';
                    $menuFirstData[$fk]['li'][] = $sv;
                    $menuFirstData[$fk]['closeUl'] = '</ul>';
                }
            }
            $menuFirstData[$fk]['closeLi'] = '</li>';
        }

        $tree = "<ul class='menu__first none'>";

        $callback = function ($v, $k) use (&$tree) {
          if ($k == 'openLi' || $k == 'openUl' || $k == 'li' || $k == 'closeUl' || $k == 'closeLi') $tree .= $v;
        };

        array_walk_recursive($menuFirstData, $callback);

        $tree .= "</ul>";

        return $tree;

    }

}
