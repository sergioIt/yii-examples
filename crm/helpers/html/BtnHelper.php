<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 06.12.17
 * Time: 13:52
 */

namespace app\helpers\html;

use app\helpers\param\EnvParam;
use app\helpers\PhoneFilter;
use app\helpers\RbacHelper;
use app\models\AppLog;
use app\models\CustomerBasisReward;
use app\models\Support;
use app\services\AsteriskService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use yii\base\Exception;
use yii\helpers\Html;
use app\helpers\param\ExternalCallEngineParam;
use Yii;

/**
 * Class BtnHelper
 * @package app\helpers\html
 */
class BtnHelper extends Html
{
    /**
     * Generates click-to-call button
     * used in card/view or customer/view for role 'seller'
     *
     * @param string $phone
     * @param int $userId
     * @param string $countryCode
     * @return string generated html (or empty string)
     */
    public static function clickToCallBtn(string $phone, int $userId, string $countryCode)
    {
        if ($phone === '') {

            Yii::warning('empty input phone', AppLog::HTML_CLICK_TO_CALL_LOG);

            return '';
        }

        if (! Yii::$app->user->can('card.click-to-call')
            && !RbacHelper::can([
                RbacHelper::ROLE_SELLER_SUPER_LIGHT,
                RbacHelper::ROLE_SELLER_LIGHT,
                RbacHelper::ROLE_SELLER,
            ])
        ) {
            return '';
        }


        $url = ExternalCallEngineParam::clickToCallUrl();

        if ($url === '') {

            Yii::warning('click-to-call helper: empty url at config click_to_call_url key ', AppLog::HTML_CLICK_TO_CALL_LOG);

            return '';
        }

        $phoneFilter = new PhoneFilter();

        $phone = $phoneFilter->prepareForClickToCall($phone, $countryCode);

        $phoneFilter->validateLength($phone, $countryCode);

        $class = (! $phoneFilter->phoneIsValid) ? 'warning' : 'success';

        return self::button(
            '',
            [
                'class' => 'btn btn-'.$class.' btn-xs glyphicon glyphicon-earphone btn_click_to_call',
                'data-url' => $url . '?agent=' . $userId . '&phone=' . $phone,
                'title' => Yii::t('app', 'Card.Customer.ClickToCall'),
            ]);

    }



    /**
     * @param $affectedCustomer CustomerBasisReward
     * @return string
     */
    public static function updateAffectedDateCustomerBtn($affectedCustomer){


        if($affectedCustomer->first_deposit_date === null){
            return '';
        }

        return  self::a(Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>',
            ['class' => 'btn btn-success btn-xs']),
            '#',
            [
                'class' => 'btn_update_affected_date',
                'title' => Yii::t('app', 'Customer.List.UpdateAffectedDate.Button.Title'),
                'data-toggle' => 'modal',
                'data-target' => '#update_affected_date_modal',
                'data-customer_id' => $affectedCustomer->customer_id,
                'data-affected' => $affectedCustomer->affected,
                'padding' => '5px'
            ]);
    }

}
