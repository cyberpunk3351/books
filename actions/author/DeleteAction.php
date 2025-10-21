<?php

namespace app\actions\author;

use app\services\AuthorService;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class DeleteAction extends Action
{
    public AuthorService $authorService;

    public function run(int $id)
    {
        $model = $this->authorService->find($id);
        if ($model === null) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        $this->authorService->delete($model);
        Yii::$app->session->setFlash('success', 'Автор удален.');

        return $this->controller->redirect(['index']);
    }
}
