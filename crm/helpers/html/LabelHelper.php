<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 06.12.17
 * Time: 18:43
 */

namespace app\helpers\html;


use app\helpers\HtmlHelper;
use app\models\Card;
use app\models\CustomerBonus;
use app\models\Customers;
use app\models\Payment;
use app\models\Payments;
use app\services\BonusService;
use Yii;

/**
 * Class LabelHelper
 * @package app\helpers\html
 */
class LabelHelper extends HtmlHelper
{


    /**
     * @param Payment $payment
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidArgumentException
     */
   public static function paymentStatusLabel(Payment $payment): string
   {
       $statusData = self::getPaymentStatusData();

       $options = [
           'class' => 'label label-'.  $statusData[$payment->status]['label'],
       ];

       if (
           $payment->isOffline()
            && $payment->isApproved()
            && $payment->hasLastApproveStatusChange()
       ) {
           $options['data-toggle'] = 'popover';
           $options['data-content'] = 'approved at: ' .
           \Yii::$app->formatter->asDatetime($payment->lastApproveStatusChange->date_time);
           HtmlHelper::addCssClass($options, 'clickable');
       }

       // for error payment with billing 'sterling' show error message
       if ($payment->isError())
       {
           $options['data-toggle'] = 'popover';
           $options['data-content'] = 'error: ' . $payment->message;

           HtmlHelper::addCssClass($options, 'clickable');
       }

       if ($payment->isDeclined()) {
           $options['data-toggle'] = 'popover';
           $options['data-content'] = 'decline reason: ' . $payment->getDeclineReason();

           HtmlHelper::addCssClass($options, 'clickable');
       }

       return self::tag('span', $statusData[$payment->status]['name'], $options);
   }


    /**
     * @return array
     */
    public  static function getVerificationStatusData(){

        return [

            Customers::STATUS_NOT_VERIFIED => [
                'name' => Yii::t('app','Customer.Status.NotVerified'),
                'label' => 'default',
            ],

            Customers::STATUS_REQUESTED_VERIFICATION => [
                'name' => Yii::t('app','Customer.Status.RequestedVerification'),
                'label' => 'warning',
            ],

            Customers::STATUS_VERIFIED => [
                'name' => Yii::t('app','Customer.Status.Verified'),
                'label' => 'success',
            ],
            Customers::STATUS_DECLINED_VERIFICATION => [
                'name' => Yii::t('app','Customer.Status.DeclinedVerification'),
                'label' => 'danger',
            ],
        ];
    }

    protected static function getPaymentStatusData(){

        $names = Payment::getStatusList();

        return [

            Payments::STATUS_PENDING => [
                'name' => $names[Payments::STATUS_PENDING],
                'label' => 'default',
            ],

            Payments::STATUS_APPROVED => [
                'name' => $names[Payments::STATUS_APPROVED],
                'label' => 'success',
            ],

            Payments::STATUS_ERROR => [
                'name' => $names[Payments::STATUS_ERROR],
                'label' => 'danger',
            ],
            Payments::STATUS_DECLINED => [
                'name' => $names[Payments::STATUS_DECLINED],
                'label' => 'danger',
            ],


        ];

    }

    /**
     * @return array
     */
    protected static function getCardStatusData(){

        return [

            Card::STATUS_NEW => [

                'name' => 'new',
                'label' => 'success',
            ],
             Card::STATUS_FAKE => [

                'name' => 'fake',
                'label' => 'default',
            ],
            Card::STATUS_IN_PROGRESS => [

                'name' => 'in_progress',
                'label' => 'warning',

            ],
             Card::STATUS_NOT_ON_PHONE => [

                'name' => 'not_on_phone',
                'label' => 'warning_dark',

            ],
            Card::STATUS_DECLINE => [

                'name' => 'decline',
                'label' => 'danger',
            ],
            Card::STATUS_APPROVE => [

                'name' => 'approve',
                'label' => 'info',
            ],


        ];

    }


    /**
     * @param $card Card
     * @return  string generated html label
     */
    public static function getCardCurrentStatusLabel($card){

        $options = [];

        $statusData = self::getCardStatusData();


        if(! array_key_exists($card->status,  $statusData)){

            return HtmlHelper::undefined_label();
        }

        $statusName = $statusData[$card->status]['name'];

        // todo if decline status is not current - get error,
        // todo fix in future to view decline reason at card view (status, actions)

        if($card->isDeclined()){

            $title = '';

            if($card->hasDeclineReason()){

                $title = $card->declineReason->reason;
            }

            $options['title'] = $title;
            $options['data-toggle'] = 'tooltip';
            $options['data-placement'] = 'left';
        }
        $options['class'] = 'label label-'.  $statusData[$card->status]['label'];

        return self::tag('span',$statusName,$options);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getCardLabelForStatus($status){

        $statusData = self::getCardStatusData();

        if(! array_key_exists($status,  $statusData)){

            return HtmlHelper::undefined_label();
        }

        $statusName = $statusData[$status]['name'];

        $options['class'] = 'label label-'.  $statusData[$status]['label'];

        return self::tag('span',$statusName,$options);

    }

}
