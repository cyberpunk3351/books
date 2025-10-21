<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class BookAuthor extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book_author}}';
    }

    public static function primaryKey(): array
    {
        return ['book_id', 'author_id'];
    }

    /**
     * @return ActiveQuery
     */
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
