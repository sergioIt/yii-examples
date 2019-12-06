<?php
///**
// * Created by PhpStorm.
// * User: sergio
// * Date: 27.06.18
// * Time: 15:25
// */
//
//namespace app\helpers\html;
//use yii\helpers\Html;
//use yii\helpers\Url;
//
///**
// * Class TableHelper
// * @package app\helpers\html
// */
//class TableHelper
//{
//    /**
//     * @param $customerId
//     * @param $email
//     * @param $templates
//     * @return string
//     */
//    public static function renderSendPulseButtonsTable($customerId, $email, $templates):string
//    {
//        $html = '<table class="table table-responsive table-striped">';
//
//        foreach ($templates as $mailType => $mailData) {
//
//            $row = '<tr>';
//
//            $row .= '<td>' . $mailType. '</td>';
//
//            $languages = $mailData['languages'];
//            $variables = $mailData['variables'];
//            $type = $mailData['message_type'];
//
//            foreach ($languages as $language => $bookId) {
//
//                if($bookId === null){
//
//                    $row .='<td> not set</td>';
//
//                    continue;
//                }
//
//                $row .='<td>'.
//                    Html::button($language,
//                        ['class' => 'btn btn-primary btn_send_pulse',
//                            'data-url' => Url::toRoute([
//                                'mail/send-pulse',
//                                'customerId'=> $customerId,
//                                'email' => $email,
//                                'bookId' => $bookId,
//                                'language' => $language,
//                                'type' => $type,
//                            ]),
//                            'data-variables' => json_encode($variables),
//                            'padding' => '5px'
//                        ]);
//
//            }
//
//            $row .= '</tr>';
//
//            $html .= $row;
//        }
//
//        $html .= '</table>';
//
//        return $html;
//    }
//}
