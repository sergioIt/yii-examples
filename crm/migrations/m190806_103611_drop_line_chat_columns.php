<?php

use yii\db\Migration;

/**
 * Class m190806_103611_drop_line_chat_columns
 *
 * удаляет поля, относящиеся к неиспользуемому line chat
 */
class m190806_103611_drop_line_chat_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(\app\models\Support::tableName(), 'use_line_chat');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(\app\models\Support::tableName(), 'use_line_chat',$this->tinyInteger());
    }

}
