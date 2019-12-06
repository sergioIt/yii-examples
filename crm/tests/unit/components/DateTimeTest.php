<?php
/**
 * Created by Valerii Tikhoirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 29.06.2018, 13:48
 */

namespace tests\components;

use app\components\DateTime;
use tests\unit\BaseUnit;

/**
 * Class DateTimeTest
 * @package tests\components
 */
class DateTimeTest extends BaseUnit
{
    const TIMESTAMP = 1514764800; // 2018-01-01 00:00:00
    const TRADE_DATETIME = '2018-01-01 00:00:00';
    const LOCAL_DATETIME = '2018-01-01 07:00:00'; // Asia/Bangkok

    public function testGetDateTimeObjectFromString()
    {
        $dateTimeObject = DateTime::getObjectFromTradeString(self::TRADE_DATETIME);

        $this->assertEquals($dateTimeObject->getTimezone()->getName(), DateTime::getLocalTimeZone());
        $this->assertEquals($dateTimeObject->format(DateTime::getDateTimeFormat()), self::LOCAL_DATETIME);

        $dateTimeObject = DateTime::getObjectFromLocalString(self::LOCAL_DATETIME);

        $this->assertEquals($dateTimeObject->getTimezone()->getName(), DateTime::getLocalTimeZone());
        $this->assertEquals($dateTimeObject->format(DateTime::getDateTimeFormat()), self::LOCAL_DATETIME);
    }

    public function testGetStringFromTimestamp()
    {
        $this->assertEquals(DateTime::getStringFromTimestamp(self::TIMESTAMP), self::TRADE_DATETIME);
        $this->assertEquals(DateTime::getStringFromTimestamp(self::TIMESTAMP, DateTime::getLocalTimeZone()), self::LOCAL_DATETIME);
    }

    public function testGetFormattedFromString()
    {
        $this->assertEquals(DateTime::getDateTimeFromTradeString(self::TRADE_DATETIME), '2018-01-01 07:00:00 (+7)');
        $this->assertEquals(DateTime::getDateTimeFromLocalString(self::LOCAL_DATETIME), '2018-01-01 07:00:00 (+7)');
    }

    public function testGetFormattedStringByObject()
    {
        $dateTimeNewYork = (new DateTime())->setTimestamp(self::TIMESTAMP)->setTimezone(new \DateTimeZone('America/New_York'));
        $dateUTC = (new DateTime())->setTimestamp(self::TIMESTAMP)->setTimezone(new \DateTimeZone('UTC'));
        $dateBangkok = (new DateTime())->setTimestamp(self::TIMESTAMP)->setTimezone(new \DateTimeZone('Asia/Bangkok'));
        $dateHongKong = (new DateTime())->setTimestamp(self::TIMESTAMP)->setTimezone(new \DateTimeZone('Asia/Hong_Kong'));

        $this->assertEquals(DateTime::getFormattedStringByObject($dateTimeNewYork), '2017-12-31 19:00:00 (-5)');
        $this->assertEquals(DateTime::getFormattedStringByObject($dateUTC), '2018-01-01 00:00:00 (0)');
        $this->assertEquals(DateTime::getFormattedStringByObject($dateBangkok), '2018-01-01 07:00:00 (+7)');
        $this->assertEquals(DateTime::getFormattedStringByObject($dateHongKong), '2018-01-01 08:00:00 (+8)');

        $this->assertEquals(DateTime::getFormattedStringByObject($dateHongKong, false), '2018-01-01 08:00:00');
    }

    public function testGetDateTimeFromTimestampByCurrency()
    {
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'THB'), '2018-01-01 07:00:00 (+7)');
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'RUB'), '2018-01-01 03:00:00 (+3)');
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'EUR'), '2018-01-01 00:00:00 (0)');
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'PHP'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'HKD'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromTimestampByCurrency(self::TIMESTAMP, 'USD'), '2018-01-01 00:00:00 (0)');
    }

    public function testGetDateTimeFromStringByCurrency()
    {
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'THB'), '2018-01-01 07:00:00 (+7)');
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'RUB'), '2018-01-01 03:00:00 (+3)');
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'EUR'), '2018-01-01 00:00:00 (0)');
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'PHP'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'HKD'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromTradeStringByCurrency(self::TRADE_DATETIME, 'USD'), '2018-01-01 00:00:00 (0)');

        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'THB'), '2018-01-01 07:00:00 (+7)');
        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'RUB'), '2018-01-01 03:00:00 (+3)');
        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'EUR'), '2018-01-01 00:00:00 (0)');
        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'PHP'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'HKD'), '2018-01-01 08:00:00 (+8)');
        $this->assertEquals(DateTime::getDateTimeFromLocalStringByCurrency(self::LOCAL_DATETIME, 'USD'), '2018-01-01 00:00:00 (0)');
    }

    public function testGetObjectFromStringByCurrency()
    {
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 07:00:00', 'THB')->getTimestamp());
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 03:00:00', 'RUB')->getTimestamp());
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 00:00:00', 'EUR')->getTimestamp());
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 08:00:00', 'PHP')->getTimestamp());
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 08:00:00', 'HKD')->getTimestamp());
        $this->assertEquals(self::TIMESTAMP, DateTime::getObjectFromStringByCurrency('2018-01-01 00:00:00', 'USD')->getTimestamp());
    }
}