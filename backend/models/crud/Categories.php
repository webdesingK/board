<?php

    namespace backend\models\crud;

    use Yii;
    use yii\web\ServerErrorHttpException;

    /**
     * This is the model class for table "categories".
     *
     * @property int $id
     * @property int $lft
     * @property int $rgt
     * @property int $depth
     * @property int $active
     * @property string $name
     * @property string $shortUrl
     * @property string $fullUrl
     */
    class Categories extends \yii\db\ActiveRecord {
        /**
         * {@inheritdoc}
         */
        public static function tableName() {
            return 'categories';
        }

        public function getData() {
            try {
                return self::find()->select(['id', 'name'])->asArray()->all();
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * {@inheritdoc}
         */
        public function rules() {
            return [
                [['lft', 'rgt', 'depth', 'name'], 'required'],
                [['lft', 'rgt', 'depth', 'active'], 'integer'],
                [['name', 'shortUrl', 'fullUrl'], 'string', 'max' => 255],
            ];
        }

        /**
         * {@inheritdoc}
         */
        public function attributeLabels() {
            return [
                'id' => 'ID',
                'lft' => 'Lft',
                'rgt' => 'Rgt',
                'depth' => 'Depth',
                'active' => 'Active',
                'name' => 'Name',
                'shortUrl' => 'Short Url',
                'fullUrl' => 'Full Url',
            ];
        }
    }
