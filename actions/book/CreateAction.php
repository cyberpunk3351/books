<?php

namespace app\actions\book;

use app\services\BookService;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

class CreateAction extends Action
{
    public BookService $bookService;
    public string $view = 'create';

    public function run()
    {
        $model = $this->bookService->createModel();
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

            if ($this->bookService->save($model)) {
                Yii::$app->session->setFlash('success', 'Книга успешно создана.');
                return $this->controller->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'authorList' => $this->bookService->getAuthorOptions(),
        ]);
    }
}
