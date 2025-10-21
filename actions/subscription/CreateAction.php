<?php

namespace app\actions\subscription;

use app\services\SubscriptionService;
use Yii;
use yii\base\Action;

class CreateAction extends Action
{
    public SubscriptionService $subscriptionService;
    public string $view = 'create';

    public function run(int $authorId)
    {
        $author = $this->subscriptionService->requireAuthor($authorId);
        $form = $this->subscriptionService->createForm($authorId);

        if ($form->load(Yii::$app->request->post()) && $this->subscriptionService->subscribe($form)) {
            Yii::$app->session->setFlash('success', 'Вы подписаны на автора. Мы сообщим о новых книгах.');
            return $this->controller->redirect(['author/view', 'id' => $authorId]);
        }

        return $this->controller->render($this->view, [
            'model' => $form,
            'author' => $author,
        ]);
    }
}
