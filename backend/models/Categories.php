<?php


namespace backend\models;

class Categories extends \common\models\categories\Categories {

    /**
     * @var $postData null
     *
     * В это свойство будет попадать массив $post при ajax запросе
     *
     */

    public $postData = null;

    /**
     * {@inheritdoc}
     */

    public function rules() {
        return [
            [['name', 'parent_id'], 'required'],
            ['name', 'string'],
            [['parent_id', 'active'], 'integer'],
        ];
    }

    /**
     * $postData = [
     *      'nameOfAction',
     *      'name',
     *      'id',
     *      'openedIds'
     * ]
     * @return bool
     */

    public function createNode() {
        $dataForTable = [
            'parent_id' => $this->postData['id'],
            'name' => $this->postData['name'],
            'active' => 1
        ];
        $parentNode = self::find()->where(['id' => $dataForTable['parent_id']])->one();
        if ($parentNode->active == 0) {
            $dataForTable['active'] = 0;
        }
        if (self::load($dataForTable, '')) {
            if (self::prependTo($parentNode)) {
                $lastId = self::find()->select(['id'])->where(['name' => $dataForTable['name']])->asArray()->one()['id'];
                array_push($this->postData['openedIds'], $lastId);
                return true;
            }
        }
        return false;

    }

    /**
     * $postData = [
     *      'nameOfAction',
     *      'id',
     *      'newName',
     *      'openedIds'
     * ]
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function renameNode() {
        $node = self::find()->where(['id' => $this->postData['id']])->one();
        $node->name = $this->postData['newName'];
        if ($node->update()) {
            return true;
        }
        return false;
    }

    /**
     * $postData = [
     *      'nameOfAction',
     *      'id',
     *      'direction',
     *      'siblingId',
     *      'openedIds'
     * ]
     * @return bool
     */

    public function moveNode() {
        $node = self::find()->where(['id' => $this->postData['id']])->one();
        $sibling = self::find()->where(['id' => $this->postData['siblingId']])->one();
        $result = false;
        if ($this->postData['direction'] == 'up') {
            $result = $node->insertBefore($sibling);
        }
        elseif ($this->postData['direction'] == 'down') {
            $result = $node->insertAfter($sibling);
        }
        return $result;
    }

    /**
     * $postData = [
     *      'nameOfAction',
     *      'value',
     *      'ids'
     * ]
     * @return bool
     */

    public function changeActive() {
        if (self::updateAll(['active' => $this->postData['value']], ['in', 'id', $this->postData['ids']])) {
            return true;
        }
        return false;
    }

    /**
     * $postData = [
     *      'nameOfAction',
     *      'id',
     *      'openedIds'
     * ]
     * @return bool
     */

    public function deleteNode() {
        $node = self::find()->where(['id' => $this->postData['id']])->one();
        if ($node->deleteWithChildren()) {
            return true;
        }
        return false;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */

    public function createArray() {

        $allCategories = self::find()->select(['id', 'parent_id', 'depth', 'name', 'active'])->orderBy('lft ASC')->asArray()->all();

        foreach ($allCategories as $key => $category) {

            if ($this->postData['openedIds'] && in_array($category['id'], $this->postData['openedIds'])) {
                $allCategories[$key]['class'] = null;
            }
            else {
                $allCategories[$key]['class'] = 'none';
            }

            if ($category['active']) {
                $allCategories[$key]['checkbox'] = [
                    'checked' => true,
                    'title' => 'Деактивировать?',
                    'data-active' => 1
                ];
            }
            else {
                $allCategories[$key]['checkbox'] = [
                    'checked' => false,
                    'title' => 'Активировать?',
                    'data-active' => 0
                ];
            }
        }

        return $allCategories;

    }

}