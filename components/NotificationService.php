<?php

namespace app\components;

use app\models\Book;
use app\models\Subscription;
use Yii;
use yii\base\Component;

class NotificationService extends Component
{
    public SmsPilotClient $smsClient;

    public function init(): void
    {
        parent::init();
        if (!isset($this->smsClient)) {
            $this->smsClient = Yii::$app->get('smsPilot');
        }
    }

    public function notifyNewBook(Book $book): void
    {
        $subscriptions = $this->collectSubscriptions($book);
        if (!$subscriptions) {
            return;
        }

        $message = sprintf(
            'Автор %s: новая книга "%s" (%s)',
            implode(', ', array_map(static fn ($author) => $author->full_name, $book->authors)),
            $book->title,
            $book->published_year ?? 'год не указан'
        );

        foreach ($subscriptions as $subscription) {
            $this->smsClient->send($subscription->phone, $message);
        }
    }

    /**
     * @return Subscription[]
     */
    private function collectSubscriptions(Book $book): array
    {
        $result = [];
        $uniquePhones = [];
        foreach ($book->authors as $author) {
            foreach ($author->subscriptions as $subscription) {
                if ($subscription->confirmed_at === null) {
                    continue;
                }
                $phone = $subscription->phone;
                if (isset($uniquePhones[$phone])) {
                    continue;
                }
                $uniquePhones[$phone] = true;
                $result[] = $subscription;
            }
        }

        return $result;
    }
}
