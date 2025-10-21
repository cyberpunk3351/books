<?php

namespace app\models\search;

use app\models\Author;
use yii\data\ActiveDataProvider;

class AuthorSearch extends Author
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['full_name'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return self::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Author::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['full_name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'full_name', $this->full_name]);

        return $dataProvider;
    }
}
