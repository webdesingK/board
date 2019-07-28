<?php

    namespace backend\models\categoriesFilters;

    use yii\data\ActiveDataProvider;

    class CategorySearch extends CategoryFilters {

        public function rules() {
            return [
                ['name', 'safe'],
            ];
        }

        public function search($params) {

            $query = CategoryFilters::find()->where(['id' => CategoryFilters::getIds()]);

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
