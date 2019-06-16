<?php


namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class Categories extends \common\models\categories\Categories {

    static public function getAllData() {
        return Yii::$app->cache->getOrSet('categoryMenuAllData', function () {
            return self::find()->where('active = 1')->andWhere('id > 1')->orderBy('lft ASC')->asArray()->all();
        });
    }

    static public function getParentById($id) {
        $node = self::find()->where(['id' => $id])->one();
        return $node->parents(1)->select(['name', 'url', 'depth'])->asArray()->one();
    }

    static public function getChildrenById($id, $lvl) {
        $node = self::find()->where(['id' => $id])->one();
        return $node->children($lvl)->asArray()->all();
    }

    static public function getSiblingNodesByParentId($parentId) {
        return self::find()->select(['name', 'url', 'depth'])->where(['parent_id' => $parentId])->orderBy('lft ASC')->asArray()->all();
    }

}