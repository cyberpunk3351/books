<?php

namespace app\actions\author;

use app\services\AuthorService;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{
    public AuthorService $authorService;

    public function run(int $id)
    {
        $model = $this->authorService->find($id, ['books']);
        if ($model === null) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        return $this->controller->render('view', [
            'model' => $model,
        ]);
    }
}
