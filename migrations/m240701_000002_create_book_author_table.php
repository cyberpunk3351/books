<?php

declare(strict_types=1);

use yii\db\Migration;

class m240701_000002_create_book_author_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-book_author', '{{%book_author}}', ['book_id', 'author_id']);

        $this->createIndex('idx-book_author-book', '{{%book_author}}', 'book_id');
        $this->createIndex('idx-book_author-author', '{{%book_author}}', 'author_id');

        $this->addForeignKey(
            'fk-book_author-book',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk-book_author-author', '{{%book_author}}');
        $this->dropForeignKey('fk-book_author-book', '{{%book_author}}');
        $this->dropTable('{{%book_author}}');
    }
}
