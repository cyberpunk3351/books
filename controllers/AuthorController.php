<?php

namespace app\controllers;

use app\actions\author\CreateAction;
use app\actions\author\DeleteAction;
use app\actions\author\IndexAction;
use app\actions\author\UpdateAction;
use app\actions\author\ViewAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthorController extends Controller
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
        $authorService = Yii::$app->get('authorService');

        return [
            'index' => [
                'class' => IndexAction::class,
                'authorService' => $authorService,
            ],
            'view' => [
                'class' => ViewAction::class,
                'authorService' => $authorService,
            ],
            'create' => [
                'class' => CreateAction::class,
                'authorService' => $authorService,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'authorService' => $authorService,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'authorService' => $authorService,
            ],
        ];
    }
}
