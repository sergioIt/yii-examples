<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.06.18
 * Time: 18:26
 */

namespace app\components;

/**
 * Class Connection
 * @package app\components
 */
class Connection extends \yii\db\Connection
{
    public $errMessage;

    /**
     *
     */
    const CONNECTION_ATTEMPTS_LIMIT = 5;

    /**
     *
     */
    public function open()
    {
        $i = 0;

        if($this->pdo !== null){

            return true;
        }

        do {
            $i++;

            try {
                parent::open();

            } catch (\PDOException $e) {

                $this->errMessage = 'exception: '.$e->getMessage();
            }

            if($this->pdo !== null){

                return true;
            }

        } while ($i < self::CONNECTION_ATTEMPTS_LIMIT);

        $this->errMessage .= 'db connection attempts limit ('.self::CONNECTION_ATTEMPTS_LIMIT.') exceed';

        \Yii::error($this->errMessage);

        return true;

    }

}
