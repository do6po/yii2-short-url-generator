<?php

use yii\db\Migration;

/**
 * Handles the creation of table `conversions`.
 */
class m180720_063300_create_conversions_table extends Migration
{
    const TABLE_NAME = '{{%conversions}}';

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
            'url_id' => $this->integer(),
            'ip' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_conversions_urls',
            self::TABLE_NAME,
            'url_id',
            '{{%urls}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_conversions_urls', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
