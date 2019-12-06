<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 21.11.16
 * Time: 14:09
 */

namespace app\traits;
use app\components\DateTime;
use app\helpers\param\CurrencyParam;
use app\helpers\param\TimeZoneParam;

/**
 * Class DateTimeTrait
 * @package app\traits
 *
 * @deprecated
 * @see DateTime
 */
class DateTimeTrait extends \DateTime
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    const DATE_DAY_FORMAT = 'Y-m-d';
    const SECONDS_IN_HOUR = 3600;

    /**
     * Get date time sting by given unix timestamp
     *
     * смысл тут такой: дата должна создаваться по timestamp с учётом зоны, к который этоот timestamp сохранятяется
     * и дополнительно форматироваться в тот часовой пояс, в котором используется  crm
     * для siam option эти пояса совпадают: Asia/Bangkok
     * для irontrade  (в этом проекте - vn crm) это пояса различны:
     * сохраняется в UTC, выводиться дожно в Asia/Bangkok
     *
     * особый слуай offline платёж: paid_date вводится вручную по времени crm (Asia/Bangkok)
     * сохраняется в виде целого числа, соответстующего  epoch timestamp по UTC
     * а выводся должно так же как и всё остальное по местному времени crm
     *
     * @param $stamp int | string epoch time stamp
     * @param $useLocalTimezone bool if true, set local timezone after set init timezone
     * @return string
     *
     * @deprecated
     * @see DateTime::getDateTimeLocalNow()
     */
    public static function createFromTimestamp($stamp, $useLocalTimezone = true){

        if($stamp === null){
            return '';
        }
        $date = new self();
        $zoneForCreate = TimeZoneParam::getTradeTimestampZone();
        $tzInit = new \DateTimeZone($zoneForCreate);
        $date->setTimestamp($stamp);

        $date->setTimezone($tzInit);

        if($useLocalTimezone && TimeZoneParam::isTradeTimeZoneDiffersFromLocale()){

            $tzLocal = new \DateTimeZone(TimeZoneParam::getLocalTimezone());
            $date->setTimezone($tzLocal);
        }

        $formatted = $date->format(self::DATE_FORMAT);

        unset($date);
        unset($tzInit);
        return $formatted;

    }

    /**
     * @param $stamp int
     * @param $currency string
     * @return string
     */
    public static function createFromTimestampAndCurrency($stamp, $currency) {
        if($stamp === null){
            return '';
        }

        $timezone = CurrencyParam::getTimezoneByCurrency($currency);
        $date = new self();
        $tzInit = new \DateTimeZone($timezone);
        $date->setTimestamp($stamp);

        $date->setTimezone($tzInit);

        $offset = $date->getOffset();
        $offsetFormat = $offset<0?$offset/self::SECONDS_IN_HOUR:'+'.($offset/self::SECONDS_IN_HOUR);

        $formatted = $date->format(self::DATE_FORMAT) . ' (' . $offsetFormat . ')';

        unset($date);
        unset($tzInit);
        return $formatted;
    }

    /**
     * @param $stamp
     * @param bool $useLocalTimezone
     * @return DateTimeTrait|string
     */
    public static function getObjectFromTimestamp($stamp, $useLocalTimezone = true){

        if($stamp === null){
            return '';
        }
        $date = new self();
        $zoneForCreate = TimeZoneParam::getTradeTimestampZone();
        $tzInit = new \DateTimeZone($zoneForCreate);
        $date->setTimestamp($stamp);

        $date->setTimezone($tzInit);

        if($useLocalTimezone && TimeZoneParam::isTradeTimeZoneDiffersFromLocale()){

            $tzLocal = new \DateTimeZone(TimeZoneParam::getLocalTimezone());
            $date->setTimezone($tzLocal);
        }

        return $date;
    }

    /**
     * Get formatted string of current moment DateTime object
     * @return \DateTime
     */
    public static function getNowString(){

        return self::getNowObject()->format(self::DATE_FORMAT);
    }

    /**
     * Get date time for current moment using local timezone
     *
     * @return \DateTime
     */
    public static function getNowObject(){

        $timeZone = new \DateTimeZone(TimeZoneParam::getLocalTimezone());
        return  (new \DateTime('now',$timeZone));
    }

    /**
     * Get date time for incoming string using local timezone
     *
     * @param $string
     * @return \DateTime
     */
    public static function getObjectFromString($string)
    {
        $timeZone = new \DateTimeZone(TimeZoneParam::getLocalTimezone());

        return new self($string, $timeZone);
    }

    /**
     * @param $string - UTC datetime string
     * @param $currency
     * @return string
     */
    public static function createFromStringAndCurrency($string, $currency): string
    {
        return self::createFromTimestampAndCurrency(
            (new self($string, new \DateTimeZone('UTC')))->getTimestamp(),
            $currency
        );
    }

    /**
     * Append timezone shift (+7 etc ) by given currency (time zone is getting by currency from local app params)
     *
     * @param $dateString
     * @param $currency
     * @return string
     */
    public function appendTimeZoneToStringByCurrency($dateString, $currency){

        if($dateString === null || $dateString === ''){

            return '';
        }

        $obj = self::getObjectFromString($dateString);

        if($obj === false){

            return $dateString;
        }

        return $this->createFromTimestampAndCurrency($obj->getTimestamp(), $currency);

    }


    /**
     * Append timezone shift (+7 etc ) by given time zone
     *
     * @param $dateString string
     * @param $timeZone string
     * @return string
     */
    public static function appendTimeZoneToStringByZone($dateString, $timeZone){


        $obj = self::getObjectFromString($dateString);

        if($obj === false){

            return $dateString;
        }

        if($timeZone === '' || $timeZone === null){

            return $obj->format(self::DATE_FORMAT);
        }

        $date = new self();
        $tzInit = new \DateTimeZone($timeZone);

        $date->setTimestamp($obj->getTimestamp());

        $date->setTimezone($tzInit);

        $offset = $date->getOffset();
        $offsetFormat = $offset<0?$offset/self::SECONDS_IN_HOUR:'+'.($offset/self::SECONDS_IN_HOUR);

        $formatted = $date->format(self::DATE_FORMAT) . ' (' . $offsetFormat . ')';

        unset($date);
        unset($tzInit);
        return $formatted;
    }

    /**
     * Append timezone offset to date string as is, assume that dateString is already in this specified timezone
     * Used only for missed call, because we get date of missed call in local time, corresponding to country
     *
     * @param $dateString
     * @param $timezone
     * @return string
     */
    public function appendTimeZoneToStringByZoneAsIs($dateString, $timezone){

        // this is to avoid breaking, just return unformatted date as it comes
        if($timezone === '' || $timezone === null){

            return $dateString;
        }

        $tzObject = new \DateTimeZone($timezone);

        $date = self::createFromFormat(self::DATE_FORMAT,$dateString, $tzObject);

        $offset = $date->getOffset();
        $offsetFormat = $offset<0?$offset/self::SECONDS_IN_HOUR:'+'.($offset/self::SECONDS_IN_HOUR);

        $formatted = $date->format(self::DATE_FORMAT) . ' (' . $offsetFormat . ')';

        unset($date);
        unset($tzInit);
        return $formatted;
    }


    /**
     * @param \DateInterval $dateInterval
     * @return int seconds
     */
    public static function dateIntervalToSeconds($dateInterval)
    {
        $timeZone = new \DateTimeZone(TimeZoneParam::getLocalTimezone());
        $reference = new \DateTimeImmutable('now', $timeZone);
        $endTime = $reference->add($dateInterval);

        return $endTime->getTimestamp() - $reference->getTimestamp();
    }

    /**
     * @param int $seconds
     * @return bool|\DateInterval
     */
    public static function secondsToDateInterval($seconds)
    {
        $seconds = (int)$seconds;
        return (new \DateTime("@0"))->diff(new \DateTime("@{$seconds}"));
    }
}
