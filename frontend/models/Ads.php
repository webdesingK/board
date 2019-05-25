<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property int $id
 * @property string $name
 * @property int $id_category
 * @property int $id_city
 */
class Ads extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ads';
    }

    /**
     * {@inheritdoc}
     */
//    public function rules()
//    {
//        return [
//            [['id_category', 'id_city'], 'integer'],
//            [['name'], 'string', 'max' => 255],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
//    public function attributeLabels() {
//        return [
//            'id' => 'ID',
//            'name' => 'Name',
//            'id_category' => 'Id Category',
//            'id_city' => 'Id City',
//        ];
//    }

    public function getAdsData($idCity, $idCategory) {

        $ads = [];

        if (!$idCity && !$idCategory) {
            $ads = self::find()->select('name')->asArray()->all();
        }
        elseif ($idCity && $idCategory) {
            $ads = self::find()->select('name')->where(['id_city' => $idCity, 'id_category' => $idCategory])->asArray()->all();
        }
        elseif ($idCity && !$idCategory) {
            $ads = self::find()->select('name')->where(['id_city' => $idCity])->asArray()->all();
        }
        elseif (!$idCity && $idCategory) {
            $ads = self::find()->select('name')->where(['id_category' => $idCategory])->asArray()->all();
        }

        return $ads;

    }
}
