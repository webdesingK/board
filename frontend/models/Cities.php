<?php


namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

class Cities extends \common\models\cities\Cities {

    /**
     * @return array|\yii\db\ActiveRecord[]
     */

    static public function getAllData() {
        return Yii::$app->cache->getOrSet('cityMenuAllData', function () {
            return self::find()->select(['id', 'depth', 'name', 'url'])->where('active = 1')->orderBy('lft ASC')->asArray()->all();
        });
    }

    static public function getRoot() {
        return self::getAllData()[0];
    }

    static public function getCityByUrl($url) {
        return self::find()->select(['name', 'url'])->where('url = :url')->addParams([':url' => $url])->andWhere(['active' => 1])->asArray()->one();
    }

}