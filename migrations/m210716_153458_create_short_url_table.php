<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%short_url}}`.
 */
class m210716_153458_create_short_url_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%short_url}}', [
            'id' => $this->primaryKey(),
            'url' => $this->text()->notNull(),
            'short' => $this->string(255)->notNull()->unique(),
            'redirects' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%short_url}}');
    }
}
