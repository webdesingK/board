<?php

    namespace adminPanel\models\treeManagers;

    use Yii;

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
         * @return bool
         * @throws \Throwable
         * @throws \yii\db\StaleObjectException
         */

        public function renameNode() {

            $node = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();
            $parent = $node->parents(1)->one();
            $shortUrl = null;
            $fullUrl = null;
            if ($parent && $parent->depth < 3) {
                $shortUrl = preg_replace('/\s/', '-', $this->postData['newName']);
                if ($parent->fullUrl) {
                    $fullUrl = $parent->fullUrl . '/' . $shortUrl;
                }
                else {
                    $fullUrl = '/' . $shortUrl;
                }
            }
            $node->name = $this->postData['newName'];
            $node->shortUrl = $shortUrl;
            $node->fullUrl = $fullUrl;
            if ($node->update()) {
                return true;
            }
            return false;
        }

        /**
         * @return bool
         */

        public function moveNode() {
            $node = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();
            $sibling = self::find()->where('id = :siblingId')->addParams([':siblingId' => $this->postData['siblingId']])->one();
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
         * @return bool
         */

        public function changeActive() {
            $ids = [];
            if (!empty($this->postData['ids'])) {
                foreach ($this->postData['ids'] as $id) {
                    $ids[] = (int)$id;
                }
            }
            if (self::updateAll(['active' => $this->postData['value']], ['in', 'id', $ids])) {
                return true;
            }
            return false;
        }

        /**
         * @return bool
         */

        public function deleteNode() {
            $node = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();
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