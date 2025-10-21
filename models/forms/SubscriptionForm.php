<?php

namespace app\models\forms;

use yii\base\Model;

class SubscriptionForm extends Model
{
    public int $authorId;
    public string $phone = '';
    public ?string $subscriberName = null;

    public function rules(): array
    {
        return [
            [['authorId', 'phone'], 'required'],
            [['authorId'], 'integer'],
            [['subscriberName'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,15}$/'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Телефон',
            'subscriberName' => 'Имя',
        ];
    }

}
