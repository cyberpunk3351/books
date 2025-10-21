<?php

declare(strict_types=1);

use yii\db\Migration;

class m240701_000003_create_subscription_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'subscriber_name' => $this->string(255)->defaultValue(null),
            'confirmation_code' => $this->string(16)->defaultValue(null),
            'confirmed_at' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-subscription-author_phone', '{{%subscription}}', ['author_id', 'phone'], true);
        $this->createIndex('idx-subscription-phone', '{{%subscription}}', 'phone');

        $this->addForeignKey(
            'fk-subscription-author',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk-subscription-author', '{{%subscription}}');
        $this->dropTable('{{%subscription}}');
    }
}
