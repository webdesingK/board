<?php


namespace console\models;


class Categories extends \common\models\categories\Categories {

    static public function getChildrenOfRoot() {
        $root = self::find()->roots()->one();
        return $root->children(1)->all();
    }

    public function getChildren($id) {

        $node = self::find()->where(['id' => $id])->one();
        $allChildren = $node->children()->all();

        $ids = [];

        foreach ($allChildren as $key => $child) {

            $children = $child->children()->all();

            if (!empty($children)) {
                foreach ($children as $item) {
                    array_push($ids, ['id' => $item->id, 'name' => $item->name]);
                }
            }
            else {
                if ($child->depth < 3) {
                    array_push($ids, ['id' => $child->id, 'name' => $child->name]);
                }
            }
        }
        return $ids;
    }

}