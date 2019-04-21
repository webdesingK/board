<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

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
            [['name', 'parent_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'name' => 'Название',
            'parent_id' => 'Родительская категория'
        ];
    }

    static public function createTree() {

        $allCats = self::find()->all();
        $cats = [];

        foreach ($allCats as $cat) {
            $cats[$cat->parent_id][] = [
                'name' => $cat->name,
                'id' => $cat->id,
            ];
        }

        function create($cats, $parentId) {

            if (isset($cats[$parentId]) && is_array($cats[$parentId])) {
                $tree = '';

                foreach ($cats[$parentId] as $cat) {
                    $tree .= '
                        <div class="category__list" data-id="' . $cat['id'] . '">
                            <div class="category__list-block">
				                <span class="name-category">' . $cat['name'] . '</span>
				                <span class="add-category" title="Добавить новую категорию">&plus;</span>
				                <span class="tabs-category" title="Развернуть">▼</span>
			                </div>
                        ';
                    $tree .= create($cats, $cat['id']);
                    $tree .= '</div>';
                }
            }
            else {
                return null;
            }
            return $tree;
        }

        return create($cats, 0);

    }
}
