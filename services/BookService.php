<?php

namespace app\services;

use app\components\NotificationService;
use app\models\Book;
use app\models\search\BookSearch;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class BookService extends Component
{
    public string $searchModelClass = BookSearch::class;

    public ?NotificationService $notificationService = null;
    public ?AuthorService $authorService = null;

    public function init(): void
    {
        parent::init();
        $this->notificationService ??= Yii::$app->get('notificationService');
        $this->authorService ??= Yii::$app->get('authorService');

        if (!$this->notificationService instanceof NotificationService) {
            throw new InvalidConfigException('NotificationService component is not configured.');
        }

        if (!$this->authorService instanceof AuthorService) {
            throw new InvalidConfigException('AuthorService component is not configured.');
        }
    }

    public function createModel(): Book
    {
        return new Book();
    }

    public function getSearchModel(): BookSearch
    {
        $searchModel = Yii::createObject($this->searchModelClass);
        if (!$searchModel instanceof BookSearch) {
            throw new InvalidConfigException('Search model must be instance of BookSearch.');
        }

        return $searchModel;
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $searchModel = $this->getSearchModel();
        return $searchModel->search($params);
    }

    public function find(int $id, array $with = ['authors']): Book
    {
        $model = Book::find()->with($with)->where(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Книга не найдена.');
        }

        return $model;
    }

    public function save(Book $book, bool $notifyOnCreate = true): bool
    {
        $isNew = $book->isNewRecord;
        if (!$book->save()) {
            return false;
        }

        if ($notifyOnCreate && $isNew) {
            $this->notificationService->notifyNewBook($book);
        }

        return true;
    }

    public function delete(Book $book): bool
    {
        return (bool) $book->delete();
    }

    public function getAuthorOptions(): array
    {
        return $this->authorService->getList();
    }
}
