<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%subscription}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => fn () => time(),
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 32],
            [['subscriber_name'], 'string', 'max' => 255],
            [['confirmation_code'], 'string', 'max' => 16],
            [['confirmed_at'], 'integer'],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,15}$/'],
            [['author_id', 'phone'], 'unique', 'targetAttribute' => ['author_id', 'phone']],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => 'Автор',
            'phone' => 'Телефон',
            'subscriber_name' => 'Имя',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function confirm(string $code): bool
    {
        if ($this->confirmation_code !== null && $this->confirmation_code === $code) {
            $this->confirmation_code = null;
            $this->confirmed_at = time();
            return (bool) $this->save(false);
        }

        return false;
    }

    public function markAsConfirmed(): void
    {
        $this->confirmation_code = null;
        $this->confirmed_at = time();
    }
}
