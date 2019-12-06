<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15.06.18
 * Time: 14:45
 *
 * @var $operations array
 * @var $columns array
 * @var $customer array
 * @var $operationsCount string
 */

use \yii\bootstrap\Alert;
use app\traits\DateTimeTrait;
use app\models\Operation;
use app\components\StatisticsFormatter;
use app\helpers\html\ConverterHelper;
use \app\helpers\HtmlHelper;

$dateTime = new DateTimeTrait();
$tableId = 'tbl_card_view_customer_archive_operations';

if (count($operations) === 0):
    Alert::begin([
        'options' => [
            'class' => 'alert-info',
        ],
    ]);
    echo 'No operations for customer yet';
    Alert::end();
endif;
?>

<? if (count($operations) === Yii::$app->params['operations']['view_limit']):
    Alert::begin([
        'options' => [
            'class' => 'alert-warning',
        ],
    ]); ?>
    <h4>Operations limit to <?= Yii::$app->params['operations']['view_limit'] ?>
        . Full count of operations: <?= $operationsCount ?> </h4>
    <? Alert::end(); ?>

<? endif; ?>

<? if (count($operations) > 0): ?>

    <table class="table table-striped table-responsive table-hover" style="font-size: 12px;" id="<?= $tableId ?>">
        <thead>
        <tr>
            <? foreach ($columns as $column): ?>
                <th class="clickable"> <?= $column ?> </th>
            <? endforeach; ?>
        </tr>
        </thead>

        <tbody>
        <? foreach ($operations as $operation): ?>
            <? $rowClass = 'default'; ?>
            <? if ($operation['profit'] > 0): $rowClass = 'success'; endif; ?>
            <? if ($operation['profit'] < 0): $rowClass = 'danger'; endif; ?>

            <tr class="<?= $rowClass ?>">
                <td><?= $operation['id'] ?>     </td>
                <td><?= ConverterHelper::prepareOperationsTimeFrame($operation['op_interval']) ?> </td>
                <td><?= //todo probably, it better to use date() here to reduce memory allocation
                    $dateTime->createFromTimestampAndCurrency($operation['open_time'], $customer['currency']) ?> </td>
                <td><?= $dateTime->createFromTimestampAndCurrency($operation['close_time'], $customer['currency']) ?> </td>
                <td><?= $operation['stock'] ?></td>
                <td><?= ($operation['op_type'] === Operation::TYPE_UP) ? 'up' : 'down' ?> </td>
                <td><?= $operation['diff'] ?> </td>
                <td><?= StatisticsFormatter::formatValue($operation['amount']) ?> </td>
                <td><?= StatisticsFormatter::formatValue($operation['profit']) ?> </td>
                <td><?= StatisticsFormatter::formatValue($operation['balance']) ?> </td>
                <td><?= StatisticsFormatter::formatValue($operation['balance_close']); ?> </td>
                <td><?= ($operation['real'] === 1) ? HtmlHelper::label_primary('real') : HtmlHelper::label('demo'); ?> </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <?php $this->registerJs('$(\'#' . $tableId . '\').tablesorter();', \yii\web\View::POS_READY); ?>
<? endif; ?>
