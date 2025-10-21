<?php

namespace app\actions\book;

use app\services\BookService;
use Yii;
use yii\base\Action;

class IndexAction extends Action
{
    public BookService $bookService;

    public function run()
    {
        $searchModel = $this->bookService->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'authorList' => $this->bookService->getAuthorOptions(),
        ]);
    }
}
