<?php

namespace app\services;

use app\models\Author;
use app\models\Subscription;
use app\models\forms\SubscriptionForm;
use yii\base\Component;
use yii\web\NotFoundHttpException;

class SubscriptionService extends Component
{
    public function createForm(int $authorId): SubscriptionForm
    {
        return new SubscriptionForm(['authorId' => $authorId]);
    }

    public function requireAuthor(int $authorId): Author
    {
        $author = Author::findOne($authorId);
        if ($author === null) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        return $author;
    }

    public function subscribe(SubscriptionForm $form): ?Subscription
    {
        if (!$form->validate()) {
            return null;
        }

        $subscription = Subscription::findOne([
            'author_id' => $form->authorId,
            'phone' => $form->phone,
        ]);

        if ($subscription === null) {
            $subscription = new Subscription([
                'author_id' => $form->authorId,
                'phone' => $form->phone,
            ]);
        }

        $subscription->subscriber_name = $form->subscriberName;
        $subscription->markAsConfirmed();

        return $subscription->save() ? $subscription : null;
    }
}
