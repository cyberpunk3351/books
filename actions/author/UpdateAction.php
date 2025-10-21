<?php

namespace app\actions\author;

use app\services\AuthorService;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class UpdateAction extends Action
{
    public AuthorService $authorService;
    public string $view = 'update';

    public function run(int $id)
    {
        $model = $this->authorService->find($id);
        if ($model === null) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        if ($model->load(Yii::$app->request->post()) && $this->authorService->save($model)) {
            Yii::$app->session->setFlash('success', 'Автор обновлен.');
            return $this->controller->redirect(['view', 'id' => $model->id]);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
