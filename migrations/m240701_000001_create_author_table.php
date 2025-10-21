<?php

declare(strict_types=1);

use yii\db\Migration;

class m240701_000001_create_author_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-author-full_name', '{{%author}}', 'full_name');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%author}}');
    }
}
