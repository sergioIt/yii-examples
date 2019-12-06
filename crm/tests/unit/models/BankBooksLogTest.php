<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 19.06.19
 * Time: 18:23
 */

namespace app\tests\unit\models;


use app\models\trade\BankBooksLog;
use app\tests\fixtures\BankBooksLogFixture;
use tests\unit\BaseUnit;

/**
 * Class BankBooksLogTest
 * @package app\tests\unit\models
 */
class BankBooksLogTest extends BaseUnit
{

    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'books' => BankBooksLogFixture::class,
        ];
    }

    /**
     *
     */
//    public function testGetData(){
//
//        $data = BankBooksLog::getData(5,'2018-09-10');
//
//        $this->assertArrayHasKey(0, $data);
//
//        $this->assertArrayHasKey('type', $data[0]);
//        $this->assertArrayHasKey('date', $data[0]);
//        $this->assertArrayHasKey('status', $data[0]);
//        $this->assertEquals('bank_book', $data[0]['type']);
//
//    }
}
