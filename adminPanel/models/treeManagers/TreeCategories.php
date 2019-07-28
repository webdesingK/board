<?php


    namespace adminPanel\models\treeManagers;

    use yii\web\ServerErrorHttpException;
    use common\models\categories\Categories;

    class TreeCategories extends Categories {

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
         * @throws ServerErrorHttpException
         */

        public function createNode() {

            try {
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
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function renameNode() {

            try {
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
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function moveNode() {
            try {
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
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function changeActive() {
            try {
                if (self::updateAll(['active' => $this->postData['value']], ['in', 'id', $this->postData['ids']])) {
                    return true;
                }
                return false;
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function deleteNode() {
            try {
                $node = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();
                if ($node->deleteWithChildren()) {
                    return true;
                }
                return false;
            }
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }

        /**
         * @return array|\yii\db\ActiveRecord[]
         * @throws ServerErrorHttpException
         */

        public function createArray() {

            try {
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
            catch (\Throwable $e) {
                throw new ServerErrorHttpException('Что то пошло не так ...');
            }
        }
    }