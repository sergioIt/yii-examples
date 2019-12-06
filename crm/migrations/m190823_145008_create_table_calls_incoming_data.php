<?php

use yii\db\Migration;

/**
 * Class m190823_145008_create_table_calls_incoming_data
 */
class m190823_145008_create_table_calls_incoming_data extends Migration
{
    const TABLE_NAME = 'calls_incoming_data';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id'          => $this->primaryKey(),
            'phone'       => $this->string(50),
            'customer_id' => $this->integer()->null(),
            'type'        => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
