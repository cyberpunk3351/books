<?php

namespace app\controllers;

use app\actions\subscription\CreateAction;
use Yii;
use yii\web\Controller;

class SubscriptionController extends Controller
{
    public function actions(): array
    {
        return [
            'create' => [
                'class' => CreateAction::class,
                'subscriptionService' => Yii::$app->get('subscriptionService'),
            ],
        ];
    }
}
