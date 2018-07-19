<?php

use yii\db\Migration;

/**
 * Handles the creation of table `urls`.
 */
class m180718_205536_create_urls_table extends Migration
{
    const TABLE_NAME = 'urls';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'long' => $this->string(2083)->notNull(),
            'short' => $this->string(64)->notNull(),
            'expired_at' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
