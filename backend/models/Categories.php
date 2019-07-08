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
            ['name', 'required'],
            ['name', 'string'],
            ['active', 'integer'],
            [['shortUrl', 'fullUrl'], 'safe']
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

        $parentNode = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();

        $shortUrl = preg_replace('/\s/', '-', $this->postData['name']);

        if ($parentNode->depth == 0) {
            $fullUrl = '/' . $shortUrl;
        }
        elseif ($parentNode->depth < 3) {
            $fullUrl = $parentNode->fullUrl . '/' . $shortUrl;
        }
        else {
            $shortUrl = null;
            $fullUrl = null;
        }

        $dataForTable = [
            'name' => $this->postData['name'],
            'fullUrl' => $fullUrl,
            'shortUrl' => $shortUrl,
            'active' => 1
        ];
        if ($parentNode->active == 0) {
            $dataForTable['active'] = 0;
        }
        if (self::load($dataForTable, '')) {
            if (self::appendTo($parentNode)) {
                $lastId = self::find()->max('id');
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
        $node->url = preg_replace('/\s/', '-', $this->postData['newName']);
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

        $allCategories = self::find()->select(['id', 'depth', 'name', 'active'])->orderBy('lft ASC')->asArray()->all();

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