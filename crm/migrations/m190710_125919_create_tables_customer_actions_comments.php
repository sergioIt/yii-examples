<?php

use yii\db\Migration;

/**
 * Class m190710_125919_create_tables_customer_actions_comments
 */
class m190710_125919_create_tables_customer_actions_comments extends Migration
{
    const ACTIONS_TABLE = 'customers_actions';
    const COMMENTS_TABLE = 'customers_comments';

    const FK_ACTIONS_CUSTOMER = 'fk_customers_actions_customer';
    const FK_COMMENTS_CUSTOMER = 'fk_customers_comments_customer';

    const FK_ACTIONS_USER = 'fk_customers_actions_user';
    const FK_COMMENTS_USER = 'fk_customers_comments_user';

    const CUSTOMERS_TABLE = 'customers';
    const USERS_TABLE = 'supports';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable(self::ACTIONS_TABLE, [
            'id'  => $this->primaryKey(),
            'created'  => $this->timestamp(0)->defaultExpression('CURRENT_TIMESTAMP'),
            'customer_id'  => $this->integer()->notNull(),
            'user_id'  => $this->integer()->notNull(),
            'type'  => $this->string(24)->notNull(),

        ]);


        $this->createTable(self::COMMENTS_TABLE, [
            'id'  => $this->primaryKey(),
            'created'  => $this->timestamp(0)->defaultExpression('CURRENT_TIMESTAMP'),
            'customer_id'  => $this->integer()->notNull(),
            'user_id'  => $this->integer()->notNull(),
            'text'  => $this->text()->notNull(),
        ]);

        $this->addForeignKey(self::FK_ACTIONS_CUSTOMER,self::ACTIONS_TABLE, 'customer_id', self::CUSTOMERS_TABLE, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(self::FK_ACTIONS_USER,self::ACTIONS_TABLE, 'user_id', self::USERS_TABLE, 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey(self::FK_COMMENTS_CUSTOMER,self::COMMENTS_TABLE, 'customer_id', self::CUSTOMERS_TABLE, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(self::FK_COMMENTS_USER,self::COMMENTS_TABLE, 'user_id', self::USERS_TABLE, 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(self::FK_COMMENTS_USER, self::COMMENTS_TABLE);
        $this->dropForeignKey(self::FK_COMMENTS_CUSTOMER, self::COMMENTS_TABLE);

        $this->dropForeignKey(self::FK_ACTIONS_USER, self::ACTIONS_TABLE);
        $this->dropForeignKey(self::FK_ACTIONS_CUSTOMER, self::ACTIONS_TABLE);


        $this->dropTable(self::COMMENTS_TABLE);
        $this->dropTable(self::ACTIONS_TABLE);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190710_125919_create_tables_customer_actions_comments cannot be reverted.\n";

        return false;
    }
    */
}
