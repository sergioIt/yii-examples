<?php

use yii\db\Migration;

/**
 * Class m190806_110413_drop_column_customers_line_chat_init
 */
class m190806_110413_drop_column_customers_line_chat_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(\app\models\Customers::tableName(),'line_chat_init');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(\app\models\Customers::tableName(),'line_chat_init', $this->boolean());
    }

}
