<?php

use yii\db\Migration;

/**
 * Class m190826_143310_add_column_created_for_call_incoming_data_table
 */
class m190826_143310_add_column_created_for_call_incoming_data_table extends Migration
{
    const TABLE_NAME = 'calls_incoming_data';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'created', $this->timestamp(0)->defaultExpression('CURRENT_TIMESTAMP'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'created');
    }
}
