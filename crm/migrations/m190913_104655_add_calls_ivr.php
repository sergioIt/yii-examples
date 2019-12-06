<?php

use yii\db\Migration;

/**
 * Class m190913_104655_add_calls_ivr
 */
class m190913_104655_add_calls_ivr extends Migration
{
    const TABLE_NAME = 'calls';
    const COLUMN_NAME = 'ivr';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, self::COLUMN_NAME, $this->integer(1)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, self::COLUMN_NAME);
    }
}
