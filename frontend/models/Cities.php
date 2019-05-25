<?php


namespace frontend\models;


class Cities extends \common\models\cities\Cities {
    /**
     * @return array|\yii\db\ActiveRecord[]
     */

    static public function createArray() {

        return self::find()->select(['name', 'depth'])->where(['active' => 1])->andWhere('id != 1')->orderBy('lft ASC')->asArray()->all();

    }

    static public function getIdByName($name) {
        return self::find()->select('id')->where(['name' => $name])->asArray()->one()['id'];
    }
}