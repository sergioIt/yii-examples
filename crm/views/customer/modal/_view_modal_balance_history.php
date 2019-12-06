<?php
/**
 * @var \app\models\Customer $customer
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\CustomerAccountLogSearch $searchModel
 */

use app\components\DateTime;
use app\components\Pjax;
use app\models\CustomerAccountLog;
use yii\grid\GridView;

Pjax::begin([
    'id'              => 'pjax-customer-balance-history-' . $customer->id,
    'formSelector'    => '#gw-customer-balance-history-' . $customer->id . ' form',
    'timeout'         => false,
    'enablePushState' => false,
]);

echo GridView::widget([
    'id'           => 'gw-customer-balance-history-' . $customer->id,
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => yii\grid\SerialColumn::class],
        'source_id',
        'date_time' => [
            'attribute' => 'date_time',
            'label' => 'Datetime',
            'format' => 'raw',
            'value'  => function ($data) use ($customer) {
                return $data['date_time'] ? DateTime::getDateTimeFromTradeString($data['date_time'], $customer->currency): null;
            },
            'filter' => \kartik\daterange\DateRangePicker::widget([
                'id'            => 'dt_daterange_' . $customer->id,
                'model'         => $searchModel,
                'attribute'     => 'date_time',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
                'options' => [
                    'id' => 'filter_date_time_' . $customer->id,
                    'class' => 'form-control',
                ],
            ]),
        ],
        'balance',
        'action'    => [
            'header'    => 'Action',
            'attribute' => 'source',
            'format'    => 'raw',
            'value'     => function ($data) {
                return CustomerAccountLog::getBalanceActionLabel($data['source']);
            },
            'filter'    => \kartik\select2\Select2::widget([
                'id'            => 'select-source_' . $customer->id,
                'model'         => $searchModel,
                'attribute'     => 'source',
                'data'          => CustomerAccountLog::getBalanceActions(),
                'options'       => [
                    'id' => 'filter_source_' . $customer->id,
                    'placeholder' => 'Select action ...',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width'      => '300px',
                ],
            ]),
        ],
    ],
]);
Pjax::end(); ?>
