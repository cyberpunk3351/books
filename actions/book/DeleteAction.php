<?php

namespace app\actions\book;

use app\services\BookService;
use Yii;
use yii\base\Action;

class DeleteAction extends Action
{
    public BookService $bookService;

    public function run(int $id)
    {
        $model = $this->bookService->find($id);
        $this->bookService->delete($model);
        Yii::$app->session->setFlash('success', 'Книга удалена.');

        return $this->controller->redirect(['index']);
    }
}
