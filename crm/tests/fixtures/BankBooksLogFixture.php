<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.06.19
 * Time: 18:25
 */

namespace app\tests\fixtures;


use yii\test\ActiveFixture;

class BankBooksLogFixture extends ActiveFixture
{
    public $db = 'db2';
    public $modelClass = 'app\models\trade\BankBooksLog';
    public $dataFile = '@tests/fixtures/data/bank_books_log.php';
}
