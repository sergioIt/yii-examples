<?php

use yii\db\Migration;

/**
 * Class m190603_143248_update_existed_customers_phone_valid
 */
class m190603_143248_update_existed_customers_phone_valid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'UPDATE customers SET phone_valid = CASE WHEN (currency = \'VND\' and phone_format similar to \'84%\' and length(phone_format) = 11) OR 
              (currency = \'THB\' and phone_format similar to \'66%\' and length(phone_format) = 11) OR 
               (currency = \'PHP\' and phone_format similar to \'63%\' and length(phone_format) = 12) OR 
               (currency = \'IDR\' and phone_format similar to \'62%\' and length(phone_format) = 13) OR 
               (currency = \'INR\' and phone_format similar to \'91%\' and length(phone_format) = 13) OR 
               (currency = \'NGN\' and phone_format similar to \'23%\' and length(phone_format) = 13) THEN TRUE ELSE FALSE  END;
        ';

        \Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->db->createCommand('UPDATE customers SET phone_valid = FALSE')->execute();
    }

}
