<?php

use app\models\Customers;
use yii\db\Migration;

/**
 * Class m190808_110520_add_column_deleted_at_customers_table
 */
class m190808_110520_add_column_deleted_at_customers_table extends Migration
{
    const COLUMN_NAME = 'deleted_at';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Customers::tableName(),self::COLUMN_NAME, $this->timestamp(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Customers::tableName(), self::COLUMN_NAME);
    }
}
