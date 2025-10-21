<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\models\Author;
use app\models\Book;
use yii\data\ActiveDataProvider;
use yii\filters\Cors;
use yii\rest\ActiveController;
use Yii;

class BookController extends ActiveController
{
    public $modelClass = Book::class;

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = fn () => $this->prepareDataProvider();

        return $actions;
    }

    private function prepareDataProvider(): ActiveDataProvider
    {
        $query = Book::find()->with('authors');
        $request = Yii::$app->request;

        if ($title = $request->get('title')) {
            $query->andWhere(['like', 'title', $title]);
        }

        $authorName = $request->get('author_name') ?? $request->get('author');
        if ($authorName) {
            $authorTable = Author::tableName();
            $query->joinWith(['authors' => static function ($authorQuery) use ($authorName, $authorTable) {
                $authorQuery->andWhere(['like', "{$authorTable}.full_name", $authorName]);
            }]);
        }

        if (($year = $request->get('published_year')) !== null) {
            $query->andWhere(['published_year' => $year]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 100],
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);
    }
}
