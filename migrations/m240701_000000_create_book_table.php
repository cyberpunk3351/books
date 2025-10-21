<?php

declare(strict_types=1);

use yii\db\Migration;

class m240701_000000_create_book_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->defaultValue(null),
            'isbn' => $this->string(32)->defaultValue(null),
            'published_year' => $this->smallInteger()->defaultValue(null),
            'cover_path' => $this->string(512)->defaultValue(null),
            'created_by' => $this->integer()->defaultValue(null),
            'updated_by' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-book-title', '{{%book}}', 'title');
        $this->createIndex('idx-book-isbn', '{{%book}}', 'isbn', true);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%book}}');
    }
}
