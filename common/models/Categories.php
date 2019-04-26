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

    function createNewNode($data) {
        $parentNode = self::find()->where(['id' => $data['id']])->one();
    }

    /**
     * @return array
     */

    static public function getDeactivateId() {
        $allData = self::findAll(['active' => 0]);
        $idArray = [];
        foreach ($allData as $key => $value) {
            $idArray[$key] = $value->id;
        }
        return $idArray;
    }

    /**
     * $category->active - столбец строки в бд
     *
     * @param $id int
     * @param $active int
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    static public function changeActivate($id, $active) {
        $category = self::findOne($id);
        $category->active = $active;
        $childrens = $category->children()->all();
        $parents = $category->parents()->all();
        $childrenIds = [];
        $parentIds = [];
        foreach ($childrens as $children) {
            $childrenIds[] = $children->id;
        }
        foreach ($parents as $parent) {
            if ($parent->id == 1) continue;
            $parentIds[] = $parent->id;
        }
        $allIds = array_merge($parentIds, $childrenIds);
        $category->update();
        self::updateAll(['active' => $active], ['in', 'id', $allIds]);
    }

    /**
     * $lastString - идентификатор строки в бд
     *
     * @param string $name
     * @return integer
     */

    public function getLastId($name) {
        $lastString = self::find()->where(['name' => $name])->one();
        return $lastString->id;
    }

    /**
     * @param $arrOpenedId array
     * @param $arrDeactivatedId array
     * @param $lastId int
     * @return string|null
     */

    static public function createTree($arrOpenedId, $arrDeactivatedId, $lastId) {

        $allCats = self::find()->all();
        $cats = [];
        foreach ($allCats as $cat) {
            $cats[$cat->parent_id][] = [
                'name' => $cat->name,
                'id' => $cat->id,
            ];
        }

        if (isset($lastId)) array_push($arrOpenedId, $lastId);

        function create($cats, $parentId, $arrOpenedId, $lastId, $arrDeactivatedId) {

            if (isset($cats[$parentId]) && is_array($cats[$parentId])) {
                $tree = '';
                $class = null;
                foreach ($cats[$parentId] as $cat) {
                    if (!is_array($arrOpenedId)) {
                        if ($cat['id'] != 1) $class = 'none';
                    }
                    else {
                        if (!in_array($cat['id'], $arrOpenedId)) $class = 'none';
                    }
                    if ($arrDeactivatedId && !empty($arrDeactivatedId) && in_array($cat['id'], $arrDeactivatedId)) {
                        $activated = [
                            'data-active' => 0,
                            'checked' => null,
                            'title' => 'Активировать?',
                        ];
                    }
                    else {
                        $activated = [
                            'data-active' => 1,
                            'checked' => 'checked',
                            'title' => 'Деактивировать?',
                        ];
                    }
                    $tree .= '
                        <div class="category__list ' . $class . '" data-id="' . $cat['id'] . '">
                            <div class="category__list-block">
				                <span class="name-category">' . $cat['name'] . '</span> 
				                <span class="add-category" title="Добавить новую категорию">&plus;</span>
                                <input type="checkbox" ' . $activated['checked'] . ' class="checkbox" title="' . $activated['title'] . '" data-active="' . $activated['data-active'] . '">
				                <span class="tabs-category" title="Развернуть">▶</span>
                                <span class="del-category" title="Удалить категорию и подкатегории">&#10008;</span>
			                </div>
                        ';
                    $tree .= create($cats, $cat['id'], $arrOpenedId, $lastId, $arrDeactivatedId);
                    $tree .= '</div>';
                }
            }
            else {
                return null;
            }
            return $tree;
        }

        return create($cats, 0, $arrOpenedId, $lastId, $arrDeactivatedId);

    }
}
