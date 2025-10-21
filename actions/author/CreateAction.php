<?php

namespace app\actions\author;

use app\services\AuthorService;
use Yii;
use yii\base\Action;

class CreateAction extends Action
{
    public AuthorService $authorService;
    public string $view = 'create';

    public function run()
    {
        $model = $this->authorService->createModel();

        if ($model->load(Yii::$app->request->post()) && $this->authorService->save($model)) {
            Yii::$app->session->setFlash('success', 'Автор добавлен.');
            return $this->controller->redirect(['view', 'id' => $model->id]);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
