<?php

namespace app\actions\author;

use app\services\AuthorService;
use Yii;
use yii\base\Action;

class IndexAction extends Action
{
    public AuthorService $authorService;

    public function run()
    {
        $searchModel = $this->authorService->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
