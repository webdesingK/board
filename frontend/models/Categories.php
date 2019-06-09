<?php


namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class Categories extends \common\models\categories\Categories {

    static public function getAllData() {
//        return Yii::$app->cache->getOrSet('categoryMenuAllData', function () {
            return self::find()->select(['id','depth', 'active', 'name', 'url'])->where('active = 1')->andWhere('id > 1')->orderBy('lft ASC')->asArray()->all();
//        });
    }

    static public function getUrls() {
        return ArrayHelper::getColumn(self::getAllData(), 'url');
    }

    static public function getNameByUrl($url) {
        return self::find()->select('name')->where('url = :url', [':url' => $url])->one()->name;
    }

    static public function getParentByName($name, $lvl) {
        $node = self::find()->where(['name' => $name])->one();
        return $node->parents($lvl)->select(['name', 'url'])->andWhere('id > 1')->asArray()->all();
    }

    static public function getChildrenByName($name) {
        $node = self::find()->where(['name' => $name])->one();
        return $node->children(1)->select(['name', 'url'])->asArray()->all();
    }

    static public function getDepthByName($name) {
        return self::find()->select('depth')->where(['name' => $name])->one()->depth;
    }

    static public function getParentIdByName($name) {
        return self::find()->select('parent_id')->where(['name' => $name])->one()->parent_id;
    }

    static public function getSiblingNodesByParentId($parentId) {
        return self::find()->select(['name', 'url'])->where(['parent_id' => $parentId])->asArray()->all();
    }

    static public function getIdByName($name) {
        return self::find()->select('id')->where(['name' => $name])->asArray()->one()['id'];
    }
}