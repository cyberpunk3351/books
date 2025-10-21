<?php

namespace app\models\search;

use app\models\Author;
use app\models\Book;
use yii\data\ActiveDataProvider;

class BookSearch extends Book
{
    public ?int $authorId = null;

    public function rules(): array
    {
        return [
            [['id', 'published_year', 'authorId'], 'integer'],
            [['title', 'isbn'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return self::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Book::find()->with('authors');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['published_year' => $this->published_year]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'isbn', $this->isbn]);

        if ($this->authorId) {
            $authorId = $this->authorId;
            $authorTable = Author::tableName();
            $query->joinWith(['authors' => static function ($authorQuery) use ($authorId, $authorTable) {
                $authorQuery->andWhere(["{$authorTable}.id" => $authorId]);
            }]);
        }

        return $dataProvider;
    }
}
