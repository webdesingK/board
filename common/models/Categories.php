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
     * $category->active - столбец строки в бд
     *
     * @param $id int
     * @param $active int
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    static public function setActive($id, $active) {
        $category = self::findOne($id);
        $category->active = $active;
        return $category->update();
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
     * @param $arrId array
     * @param $lastId int
     * @return string|null
     */

    static public function createTree($arrId, $lastId) {

        $allCats = self::find()->all();
        $cats = [];
        foreach ($allCats as $cat) {
            $cats[$cat->parent_id][] = [
                'name' => $cat->name,
                'id' => $cat->id,
            ];
        }

        if (isset($lastId)) array_push($arrId, $lastId);

        function create($cats, $parentId, $arrId, $lastId) {

            if (isset($cats[$parentId]) && is_array($cats[$parentId])) {
                $tree = '';
                $class = null;
                foreach ($cats[$parentId] as $cat) {
                    if (!is_array($arrId)) {
                        if ($cat['id'] != 1) $class = 'none';
                    }
                    else {
                        if (!in_array($cat['id'], $arrId)) $class = 'none';
                    }

                    $tree .= '
                        <div class="category__list ' . $class . '" data-id="' . $cat['id'] . '">
                            <div class="category__list-block">
				                <span class="name-category">' . $cat['name'] . '</span> 
				                <span class="add-category" title="Добавить новую категорию">&plus;</span>
                                <input type="checkbox" checked class="checkbox" title="Деактивировать?" data-active="1">
				                <span class="tabs-category" title="Развернуть">▶</span>
                                <span class="del-category" title="Удалить категорию и подкатегории">&#10008;</span>
			                </div>
                        ';
                    $tree .= create($cats, $cat['id'], $arrId, $lastId);
                    $tree .= '</div>';
                }
            }
            else {
                return null;
            }
            return $tree;
        }

        return create($cats, 0, $arrId, $lastId);

    }
}
