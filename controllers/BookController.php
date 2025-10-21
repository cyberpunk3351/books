<?php

namespace app\controllers;

use app\actions\book\CreateAction;
use app\actions\book\DeleteAction;
use app\actions\book\IndexAction;
use app\actions\book\UpdateAction;
use app\actions\book\ViewAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        $bookService = Yii::$app->get('bookService');

        return [
            'index' => [
                'class' => IndexAction::class,
                'bookService' => $bookService,
            ],
            'view' => [
                'class' => ViewAction::class,
                'bookService' => $bookService,
            ],
            'create' => [
                'class' => CreateAction::class,
                'bookService' => $bookService,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'bookService' => $bookService,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'bookService' => $bookService,
            ],
        ];
    }
}
