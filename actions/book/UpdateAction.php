<?php

namespace app\actions\book;

use app\services\BookService;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

class UpdateAction extends Action
{
    public BookService $bookService;
    public string $view = 'update';

    public function run(int $id)
    {
        $model = $this->bookService->find($id);
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

            if ($this->bookService->save($model, false)) {
                Yii::$app->session->setFlash('success', 'Книга обновлена.');
                return $this->controller->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'authorList' => $this->bookService->getAuthorOptions(),
        ]);
    }
}
