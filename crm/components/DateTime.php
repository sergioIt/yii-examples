<?php
/**
 * Created by Valerii Tikhoirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 28.06.2018, 18:58
 */

namespace app\components;

use app\helpers\param\CurrencyParam;
use app\helpers\param\TimeZoneParam;
use yii\validators\DateValidator;

/**
 * Class DateTime
 *
 * Существует две базы данных, назовем их local (db1) и trade (db2)
 * В зависимости от проекта (сервера) могут использоваться разные временные зоны.
 *  Они указаны в параметрах приложения.
 *
 * Пример:
 *  на irontrade в db1 установлена Asia/Bangkok зона, в db2 - UTC
 *  на siamoption - обе БД в Asia/Bangkok
 *
 * Класс создан для комфортного преобразования даты/времени из одной таймзоны в другую
 * Может потребоваться из любой из БД забрать дату в формате строки или unix-timestamp и преобразовать
 *  в локальную временную зону, либо в зону, определяемую валютой пользователя (currency).
 *
 * @package app\components
 */
class DateTime extends \DateTime
{
    /**
     * Date format from app config
     * @return string
     */
    public static function getDateFormat(): string
    {
        return str_replace('php:', '', \Yii::$app->formatter->dateFormat);
    }

    /**
     * Datetime format from app config
     * @return string
     */
    public static function getDateTimeFormat(): string
    {
        return str_replace('php:', '', \Yii::$app->formatter->datetimeFormat);
    }

    /**
     * Get local db timezone
     * @return string
     */
    public static function getLocalTimeZone(): string
    {
        return TimeZoneParam::getLocalTimezone();
    }

    /**
     * Get trade db2 timezone
     * @return string
     */
    public static function getTradeTimeZone(): string
    {
        return TimeZoneParam::getTradeTimestampZone();
    }

    /**
     * Check unix timestamp format
     * @param $timestamp
     * @return bool
     */
    public static function isTimestamp(int $timestamp): bool
    {
        $validator = new DateValidator();
        $validator->format = 'php:U';

        return $validator->validate($timestamp);
    }

    /**
     * Get datetime string from timestamp
     *
     * @param int $timestamp
     * @param string $timezone - convert timestamp in this timezone
     * @param string $format - output datetime format
     *
     * @return string
     */
    public static function getStringFromTimestamp(int $timestamp, string $timezone = 'UTC', $format = null): string
    {
        return (new self())
                    ->setTimestamp($timestamp)
                    ->setTimezone(new \DateTimeZone($timezone))
                    ->format($format ?? self::getDateTimeFormat());
    }

    /**
     * Datetime object from string in local timezone
     *
     * @param string $datetime
     * @return \DateTime
     */
    public static function getObjectFromLocalString(string $datetime): \DateTime
    {
        return new self($datetime, new \DateTimeZone(self::getLocalTimeZone()));
    }

    /**
     * Datetime object from string
     * @param string $datetime
     * @param string $currency
     *
     * @return \DateTime
     */
    public static function getObjectFromStringByCurrency(string $datetime, string $currency): \DateTime
    {
        $timezone = CurrencyParam::getTimezoneByCurrency($currency);

        return new self($datetime, new \DateTimeZone($timezone));
    }

    /**
     * Datetime object from trade timezone
     * @param string $datetime - datetime string for create object
     *
     * @return \DateTime
     */
    public static function getObjectFromTradeString(string $datetime): \DateTime
    {
        return (new self($datetime, new \DateTimeZone(self::getTradeTimeZone())))
            ->setTimezone(new \DateTimeZone(self::getLocalTimeZone()));
    }

    /**
     * Get formatted string with offset if need from string in trade timezone
     *
     * @param string $datetime
     * @param bool $offset - if TRUE, append timezone translation
     *
     * @return string
     */
    public static function getDateTimeFromTradeString(string $datetime, $offset = true): string
    {
        return self::getFormattedStringByObject(self::getObjectFromTradeString($datetime), $offset);
    }

    /**
     * Get formatted string from string in local timezone
     *  with offset if need
     *
     * @param string $datetime
     * @param bool $offset - if TRUE, append timezone translation
     *
     * @return string
     */
    public static function getDateTimeFromLocalString(string $datetime, bool $offset = true): string
    {
        return self::getFormattedStringByObject(self::getObjectFromLocalString($datetime), $offset);
    }

    /**
     * @param int $timestamp
     * @param bool $offset
     *
     * @return string
     *
     */
    public static function getDateTimeFromTimestamp(int $timestamp, bool $offset = true): string
    {
        $timezone = self::getLocalTimeZone();
        $dateTimeObject = (new self())->setTimestamp($timestamp)->setTimezone(new \DateTimeZone($timezone));

        return self::getFormattedStringByObject($dateTimeObject, $offset);
    }

    /**
     * @param int $timestamp
     * @param string $currency
     * @param bool $offset
     *
     * @return string
     */
    public static function getDateTimeFromTimestampByCurrency(int $timestamp, string $currency, bool $offset = true): string
    {
        $timezone = CurrencyParam::getTimezoneByCurrency($currency);
        $dateTimeObject = (new self())->setTimestamp($timestamp)->setTimezone(new \DateTimeZone($timezone));

        return self::getFormattedStringByObject($dateTimeObject, $offset);
    }

    /**
     * Get formatted datetime string by datetime string in trade timezone and currency
     *  with offset if need
     *
     * @param string $datetime
     * @param string $currency
     * @param bool $offset
     *
     * @return string
     */
    public static function getDateTimeFromTradeStringByCurrency(string $datetime, string $currency, bool $offset = true): string
    {
        $timezone = CurrencyParam::getTimezoneByCurrency($currency);
        $dateTimeObject = self::getObjectFromTradeString($datetime)
                              ->setTimezone(new \DateTimeZone($timezone));

        return self::getFormattedStringByObject($dateTimeObject, $offset);
    }

    /**
     * Get formatted datetime string by datetime string in local timezone and currency
     *  with offset if need
     *
     * @param string $datetime
     * @param string $currency
     * @param bool $offset
     *
     * @return string
     */
    public static function getDateTimeFromLocalStringByCurrency(string $datetime, string $currency, bool $offset = true): string
    {
        $timezone = CurrencyParam::getTimezoneByCurrency($currency);
        $dateTimeObject = self::getObjectFromLocalString($datetime)
                              ->setTimezone(new \DateTimeZone($timezone));

        return self::getFormattedStringByObject($dateTimeObject, $offset);
    }

    /**
     * Get formatted datetime string with offset if need from string in local timezone
     *
     * @param \DateTime $object
     * @param bool $offset
     *
     * @return string
     */
    public static function getFormattedStringByObject(\DateTime $object, bool $offset = true): string
    {
        return $object->format(self::getDateTimeFormat()) . ($offset ? ' (' . self::getOffsetString($object) . ')' : '');
    }

    /**
     * Get formatted datetime NOW
     *
     * @param bool $offset
     * @return string
     */
    public static function getDateTimeLocalNow(bool $offset = true): string
    {
        return self::getFormattedStringByObject((new self())->setTimezone(new \DateTimeZone(self::getLocalTimeZone())), $offset);
    }

    /**
     * @param \DateTime $datetime
     *
     * @return string
     */
    protected static function getOffsetString(\DateTime $datetime): string
    {
        $offset = $datetime->getOffset() / 3600;

        switch ($offset <=> 0) {
            case 0:
                return '0';
            case 1:
                return '+' . $offset;
            case -1:
            default:
                return $offset;
        }
    }
}
