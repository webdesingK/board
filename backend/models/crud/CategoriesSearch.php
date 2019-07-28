<?php

    namespace backend\models\crud;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * CategoriesSearch represents the model behind the search form of `backend\models\crud\Categories`.
     */
    class CategoriesSearch extends Categories {
        /**
         * {@inheritdoc}
         */
        public function rules() {
            return [
                [['id', 'lft', 'rgt', 'depth', 'active'], 'integer'],
                [['name', 'shortUrl', 'fullUrl'], 'safe'],
            ];
        }

        /**
         * {@inheritdoc}
         */
        public function scenarios() {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }

        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params) {
            $query = Categories::find()->where('depth < 4')->orderBy('lft ASC');

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                'lft' => $this->lft,
                'rgt' => $this->rgt,
                'depth' => $this->depth,
                'active' => $this->active,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name]);
//                ->andFilterWhere(['like', 'shortUrl', $this->shortUrl])
//                ->andFilterWhere(['like', 'fullUrl', $this->fullUrl]);

            return $dataProvider;
        }
    }
