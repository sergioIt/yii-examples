<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28.03.18
 * Time: 17:49
 */

namespace app\helpers;

use app\helpers\param\ExternalCallEngineParam;

use libphonenumber\PhoneNumberUtil;
use yii\base\Model;

/***
 * Class PhoneFilter
 * @package app\helpers
 * @property array $rejectedUsers
 * @property string $validationErrMsg
 * @property bool $phoneIsValid
 */
class PhoneFilter extends  Model
{
    public $rejectedUsers = [];

    /**
     * @var string
     */
    public $validationErrMsg;

    /**
     * @var bool
     */
    public $phoneIsValid = false;


    /**
     * @param $phone
     * @param $countryCode
     *
     * @return string prepared for click-to-call phone number
     */
    public static function prepareForClickToCall($phone, $countryCode): string
    {
        $phone =  preg_replace('/\D/', '', $phone);
        return self::processReplacements(trim($phone), $countryCode);
    }


    /**
     * @param $phone
     * @param $isoCode
     * @return mixed
     */
    public static function processReplacements($phone, $isoCode){

        $replacements = ExternalCallEngineParam::getPrefixReplacementRulesByCountryCode($isoCode);

        if($replacements === []){

            return $phone;
        }

        foreach ($replacements as $from => $to) {
            $from = addcslashes($from, '+'); // Экранирование знака +
            $phone = preg_replace('/^' . $from . '/', $to, $phone);
        }

        return $phone;
    }

    /**
     * @param array $data
     * @param string $isoCode
     * @return array
     */
    public static function processReplacementsBatch(array $data,  string $isoCode  ){

        foreach ($data as &$item){

            $item['phone'] = PhoneFilter::processReplacements( $item['phone'], $isoCode);
        }

        return $data;
    }



    /**
     * @param $phone
     * @param $countryCode
     * @return  bool
     */
    public function validateLength($phone, $countryCode){

        $filterRules = ExternalCallEngineParam::autoCallFilterRulesForCountryCode($countryCode);

        if($filterRules === []){

            $this->validationErrMsg = 'rules for length is not defined for country code '. $countryCode;
        }

        if(array_key_exists('min', $filterRules)){

            if(strlen($phone) < $filterRules['min']){

                $this->validationErrMsg = 'phone length is less than '.$filterRules['min'];
                return false;
            }
        }

        if(array_key_exists('max', $filterRules)){

            if(strlen($phone) > $filterRules['max']){

                $this->validationErrMsg = 'phone length is larger than '.$filterRules['max'];
                return false;
            }
        }

        $this->phoneIsValid = true;

        return true;

    }

    /**
     * Hide phone number, for example:
     *  +84949314215 => +8494XXX4215
     *
     * @param string|null $phone
     * @return string|null
     */
    public static function hide($phone)
    {
        $pattern = '/([\d]+)([\d]{3})([\d]{4})$/';

        return $phone ? preg_replace_callback($pattern, function ($matches) {
            return $matches[1] . str_repeat('X', strlen($matches[2])) . $matches[3];
        }, $phone) : null;
    }

    /**
     * Phone hide filter for certain roles
     *
     * @param string|null $phone
     * @return string|null
     */
    public static function hidePermissionFilter($phone)
    {
        if (RbacHelper::can([
                RbacHelper::ROLE_SELLER,
                RbacHelper::ROLE_SELLER_SUPER_LIGHT,
                RbacHelper::ROLE_SELLER_LIGHT,
                RbacHelper::ROLE_SUPPORT,
            ])) {
            return self::hide($phone);
        }

        return $phone;
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    public static function normalize($phone): string
    {
        if ($phone[0] !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
