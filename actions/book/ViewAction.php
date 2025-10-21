<?php

namespace app\actions\book;

use app\services\BookService;
use yii\base\Action;

class ViewAction extends Action
{
    public BookService $bookService;

    public function run(int $id)
    {
        $model = $this->bookService->find($id);
        return $this->controller->render('view', [
            'model' => $model,
        ]);
    }
}
