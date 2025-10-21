<?php

namespace app\controllers;

use app\actions\report\TopAuthorsAction;
use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actions(): array
    {
        return [
            'top-authors' => [
                'class' => TopAuthorsAction::class,
                'reportService' => Yii::$app->get('topAuthorReport'),
            ],
        ];
    }
}
