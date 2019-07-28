<?php

    namespace backend\models\categoriesFilters;

    use yii\data\ActiveDataProvider;

    class CategoriesSearch extends Categories {

        public function rules() {
            return [
                ['name', 'safe'],
            ];
        }

        public function search($params) {

            $query = Categories::find()->where(['id' => Categories::getIds()]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                return $dataProvider;
            }

            $query->andFilterWhere(['like', 'name', $this->name]);

            return $dataProvider;
        }
    }
