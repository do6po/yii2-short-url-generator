<?php

use yii\db\Migration;

/**
 * Class m180720_142816_alter_conversion_add_country_and_city
 */
class m180720_142816_alter_conversions_add_country_and_city extends Migration
{
    const TABLE_NAME = '{{%conversions}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'country', $this->string());
        $this->addColumn(self::TABLE_NAME, 'city', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'country');
        $this->dropColumn(self::TABLE_NAME, 'city');
    }
}
