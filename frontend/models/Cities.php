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
            return self::find()->select(['id', 'depth', 'active', 'name', 'url'])->where('active = 1')->andWhere('id > 1')->orderBy('lft ASC')->asArray()->all();
        });
    }

}