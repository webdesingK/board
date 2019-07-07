<?php


namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class Categories extends \common\models\categories\Categories {

    static public function getAllData() {
        return Yii::$app->cache->getOrSet('categoryMenuAllData', function () {
            return self::find()->where('active = 1')->andWhere('id > 1')->andWhere('depth < 4')->orderBy('lft ASC')->all();
        });
    }

    static public function getFirstLvlByUrl($url) {
        return self::find()->where(['depth' => 1])->andWhere(['active' => 1])->andWhere('shortUrl = :url')->addParams([':url' => $url])->one();;
    }

    static public function getSecondLvlByNodeAndUrl($node, $url) {
        return $node->children(1)->andWhere(['active' => 1])->andWhere('shortUrl = :url')->addParams([':url' => $url])->one();
    }

    static public function getThirdLvlByNodeAndUrl($node, $url) {
        return $node->children(1)->andWhere(['active' => 1])->andWhere('shortUrl = :url')->addParams([':url' => $url])->one();
    }

    static public function getChildrenOfRoot() {
        return self::find()->where(['depth' => 1])->andWhere(['active' => 1])->all();
    }

    static public function getParentByNode($node) {
        return $node->parents(1)->select(['name', 'fullUrl', 'depth'])->asArray()->one();
    }

    static public function getChildrenByNode($node) {
        return $node->children(1)->select(['name', 'fullUrl', 'depth'])->andWhere('depth < 4')->asArray()->all();
    }

    static public function getSiblingNodesByParent($parent) {
        return $parent->children(1)->select(['name', 'fullUrl'])->orderBy('lft ASC')->asArray()->all();
    }

    static public function getChildrenIds($category) {

        $ids = [];

        if (($category['rgt'] - $category['lft']) > 1) {
            $node = self::find()->where(['id' => $category['id']])->one();
            $firstChildren = $node->children(1)->andWhere('depth < 4')->all();

            foreach ($firstChildren as $firstChild) {
                if (($firstChild['rgt'] - $firstChild['lft'] > 1)) {
                    $secondChildren = $firstChild->children(1)->andWhere('depth < 4')->all();
                    foreach ($secondChildren as $secondChild) {
                        array_push($ids, $secondChild->id);
                    }
                }
                else {
                    array_push($ids, $firstChild->id);
                }
            }

        }

        return $ids;
    }

    static public function getParentId($id) {
        $node = self::find()->where(['id' => $id])->one();
        $parent = $node->parents()->select('id')->andWhere('depth = 1')->one();

        if ($parent === null) {
            $parentId = (int)$id;
        }
        else {
            $parentId = $parent->id;
        }

        return $parentId;
    }

    static public function getTypes($node) {
        return $node->children(1)->select(['name'])->asArray()->all();
    }

}