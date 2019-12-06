<?php

use yii\db\Migration;

/**
 * Class m190604_134322_fix_update_existed_phone_valid_for_INR
 *
 * fix предыдущей миграции, в которой по ошибке для валбты INR была указана длина 13, а должна быть 12
 */
class m190604_134322_fix_update_existed_phone_valid_for_INR extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->update(\app\models\Customers::tableName(),
            ['phone_valid' => new \yii\db\Expression('CASE WHEN (phone_format similar to \'91%\' and length(phone_format) = 12) THEN TRUE ELSE FALSE END')],
            ['currency' => 'INR']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190604_134322_fix_update_existed_phone_valid_for_INR cannot be reverted.\n";

        return true;
    }

}
