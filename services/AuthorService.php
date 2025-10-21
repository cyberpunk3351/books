<?php

namespace app\services;

use app\models\Author;
use app\models\search\AuthorSearch;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class AuthorService extends Component
{
    public string $searchModelClass = AuthorSearch::class;

    public function createModel(): Author
    {
        return new Author();
    }

    public function getSearchModel(): AuthorSearch
    {
        $searchModel = Yii::createObject($this->searchModelClass);
        if (!$searchModel instanceof AuthorSearch) {
            throw new InvalidConfigException('Search model must be instance of AuthorSearch.');
        }

        return $searchModel;
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $searchModel = $this->getSearchModel();
        return $searchModel->search($params);
    }

    public function getList(): array
    {
        return ArrayHelper::map(
            Author::find()->orderBy(['full_name' => SORT_ASC])->all(),
            'id',
            'full_name'
        );
    }

    public function find(int $id, array $with = []): ?Author
    {
        return Author::find()->with($with)->where(['id' => $id])->one();
    }

    public function save(Author $author): bool
    {
        return $author->save();
    }

    public function delete(Author $author): bool
    {
        return (bool) $author->delete();
    }
}
