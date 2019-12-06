<?php

use yii\db\Migration;

/**
 * Class m190605_131628_full_update_customers_phone_valid_after_fixed_update_attributes
 *
 * апдейтим заново поле phone_valid, потому что при синхронизации через редис для существуюющей записи это поле не апдейтилось
 */
class m190605_131628_full_update_customers_phone_valid_after_fixed_update_attributes extends Migration
{
    public function safeUp()
    {
        $sql = 'UPDATE customers SET phone_valid = CASE WHEN 
              (currency = \'VND\' and phone_format similar to \'84%\' and length(phone_format) = 11) OR 
              (currency = \'THB\' and phone_format similar to \'66%\' and length(phone_format) = 11) OR 
               (currency = \'PHP\' and phone_format similar to \'63%\' and length(phone_format) = 12) OR 
               (currency = \'IDR\' and phone_format similar to \'62%\' and length(phone_format) = 13) OR 
               (currency = \'INR\' and phone_format similar to \'91%\' and length(phone_format) = 12) OR 
               (currency = \'NGN\' and phone_format similar to \'23%\' and length(phone_format) = 13) THEN TRUE ELSE FALSE  END;
        ';

        \Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       return true;
    }
}
