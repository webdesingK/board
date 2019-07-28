<?php


    namespace adminPanel\models\treeManagers;

    use common\models\cities\Cities;
    use yii\web\ServerErrorHttpException;

    class TreeCities extends Cities {

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
                [['name', 'url', 'parent_id'], 'required'],
                [['name', 'url'], 'string'],
                [['parent_id', 'active'], 'integer'],
            ];
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function createNode() {

            try {
                $dataForTable = [
                    'parent_id' => $this->postData['id'],
                    'name' => $this->postData['name'],
                    'url' => preg_replace('/\s/', '-', $this->postData['name']),
                    'active' => 1
                ];
                $parentNode = self::find()->where('id = :id')->addParams([':id' => $dataForTable['parent_id']])->one();
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
                self::serverError();
            }
        }

        /**
         * @return bool
         * @throws ServerErrorHttpException
         */

        public function renameNode() {
            try {
                $node = self::find()->where('id = :id')->addParams([':id' => $this->postData['id']])->one();
                $node->name = $this->postData['newName'];
                $node->url = preg_replace('/\s/', '-', $this->postData['newName']);
                if ($node->update()) {
                    return true;
                }
                return false;
            }
            catch (\Throwable $e) {
                self::serverError();
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
                self::serverError();
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
                self::serverError();
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
                self::serverError();
            }
        }

        /**
         * @return array|\yii\db\ActiveRecord[]
         * @throws ServerErrorHttpException
         */

        public function createArray() {
            try {

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
            catch (\Throwable $e) {
                self::serverError();
            }
        }

        /**
         * @throws ServerErrorHttpException
         */
        private function serverError() {
            throw new ServerErrorHttpException('Что то пошло не так ...');
        }
    }