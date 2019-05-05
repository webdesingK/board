<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;

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
            [['name', 'parent_id', 'active'], 'safe']
        ];
    }

    /**
     * @param $data array
     * @return bool|int
     */

    public function createNewNode($data) {
        $dataForModel = [
            'parent_id' => $data['id'],
            'name' => $data['name']
        ];
        $parentNode = self::find()->where(['id' => $data['id']])->one();
        if (self::load($dataForModel, '') && self::prependTo($parentNode)) {
            return self::getLastId($data['name']);
        }
        else {
            return false;
        }
    }

    /**
     * @param $ids array
     * @param $value int
     * @return int
     */

    public function changeActive($ids, $value) {
        self::updateAll(['active' => $value], ['in', 'id', $ids]);
    }

    /**
     * @param $id int
     * @param $value string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function renameNode($id, $value) {
        $node = self::find()->where(['id' => $id])->one();
        $node->name = $value;
        $node->update();
    }

    public function moveUp($id, $siblingId) {
        $node = self::find()->where(['id' => $id])->one();
        $sibling = self::find()->where(['id' => $siblingId])->one();
        return $node->insertBefore($sibling);
    }

    public function moveDown($id, $siblingId) {
        $node = self::find()->where(['id' => $id])->one();
        $sibling = self::find()->where(['id' => $siblingId])->one();
        return $node->insertAfter($sibling);
    }

    /**
     * @param $id int
     * @return mixed
     */

    public function deleteNode($id) {
        $node = self::find()->where(['id' => $id])->one();
        return $node->deleteWithChildren();
    }

    /**
     * $lastString->id - идентификатор последней записанной строки в бд
     *
     * @param string $name
     * @return integer
     */

    public function getLastId($name) {
        $lastString = self::find()->where(['name' => $name])->one();
        return $lastString->id;
    }

    /**
     * @param $openedIds array
     * @param $lastId int
     * @return string|null
     */

    static public function createTree($openedIds, $lastId) {

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
				                <span class="name-category">' . $cat['name'] . '</span>
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
}
