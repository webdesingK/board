<?php

namespace common\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

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

        $all = self::find()->all();
        $cats = [];

        foreach ($all as $v) {
            $cats[$v->parent_id][] = [
                'name' => $v->name,
                'id' => $v->id,
                'depth' => $v->depth
            ];
        }

        function create($cats, $parentId) {
            if (isset($cats[$parentId]) && is_array($cats[$parentId])) {
                $tree = '<div class="parent">';
                foreach ($cats[$parentId] as $k => $v) {
                    $tree .= '<div class="name" data-depth="'.$v['depth'].'" data-id="'.$v['id'].'">'.$v['name'].' - id: '.$v['id'].'<button>удалить</button></div>';
                    $tree .= create($cats, $v['id']);
                }
                $tree .= '</div>';
            }
            else {
                return null;
            }
            return $tree;
        }

        return create($cats, 1);

    }
}
