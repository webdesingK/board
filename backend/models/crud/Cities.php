<?php

    namespace backend\models\crud;

    use Yii;

    /**
     * This is the model class for table "cities".
     *
     * @property int $id
     * @property int $lft
     * @property int $rgt
     * @property int $depth
     * @property int $parent_id
     * @property int $active
     * @property string $name
     * @property string $url
     */
    class Cities extends \yii\db\ActiveRecord {
        /**
         * {@inheritdoc}
         */
        public static function tableName() {
            return 'cities';
        }

        /**
         * {@inheritdoc}
         */
        public function rules() {
            return [
                [['lft', 'rgt', 'depth', 'parent_id', 'active', 'name'], 'required'],
                [['lft', 'rgt', 'depth', 'parent_id', 'active'], 'integer'],
                [['name', 'url'], 'string', 'max' => 255],
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
                'parent_id' => 'Parent ID',
                'active' => 'Active',
                'name' => 'Name',
                'url' => 'Url',
            ];
        }
    }
