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

    public function createArray() {
        $all = self::find()->orderBy('lft ASC')->all();

        $first = [];
        $second = [];
        $third = [];

        foreach ($all as $k => $v) {
            if ($v->depth == 1) {
                $first[] = [
                    'id' => $v->id,
                    'parent_id' => $v->parent_id,
                    'name' => $v->name,
                    'sub' => []
                ];
            }
            if ($v->depth == 2) {
                $second[] = [
                    'id' => $v->id,
                    'parent_id' => $v->parent_id,
                    'name' => $v->name,
                    'sub' => []
                ];
            }
            if ($v->depth == 3) {
                $third[] = [
                    'id' => $v->id,
                    'parent_id' => $v->parent_id,
                    'name' => $v->name
                ];
            }
        }

        foreach ($first as $firstKey => $firstValue) {
            foreach ($second as $secondKey => $secondValue) {
                if ($secondValue['parent_id'] == $firstValue['id']) {
                    foreach ($third as $thirdKey => $thirdValue) {
                        if ($thirdValue['parent_id'] == $secondValue['id']) {
                            $second[$secondKey]['sub'][] = [
                                'id' => $thirdValue['id'],
                                'parent_id' => $thirdValue['parent_id'],
                                'name' => $thirdValue['name']
                            ];
                        }
                    }
                    $first[$firstKey]['sub'][] = [
                        'id' => $secondValue['id'],
                        'parent_id' => $secondValue['parent_id'],
                        'name' => $secondValue['name'],
                        'sub' => $second[$secondKey]['sub']
                    ];
                }
            }
        }


        return $first;
    }

}
