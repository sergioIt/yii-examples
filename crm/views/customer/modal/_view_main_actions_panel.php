<?php
/**
 * @var $card app\models\Card
 * @var $userCurrency string
 */

use app\helpers\HtmlHelper;
use app\helpers\html\LabelHelper;
use app\components\DateTime;

?>

<div class="panel panel-default card_view_actions_panel">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= Yii::t('app', 'Card.View.Actions') ?></h3>
    </div>
    <div class="panel-body">
        <table class="table table-responsive table-striped card_view_table_changes"
               style="display: <?= (count($card->changes) === 0) ? 'none' : 'block' ?>; width: auto">
            <thead>
            <tr>
                <th>date</th>
                <th>seller</th>
                <th>type</th>
                <th>status</th>
            </tr>
            </thead>
            <tbody>
            <? if (count($card->changes) > 0): ; ?>
                <? /** @var $card \app\models\Card */ ?>
                <?php foreach ($card->changes as $change): ?>
                    <tr>
                        <?php /** @var $change \app\models\CardChanges */ ?>
                        <td style="font-size: 90%;"> <?= DateTime::getDateTimeFromLocalStringByCurrency($change->created, $card->localCustomer->currency); ?> </td>
                        <td> <?= $change->user ? $change->user->login : '-' ?></td>
                        <td> <?= HtmlHelper::label_default($change->getTypeText(true),'', $change->getTypeText()); ?> </td>
                        <td> <?= $change->isStatusChange() ? LabelHelper::getCardLabelForStatus($change->status) : ''; ?> </td>
                    </tr>
                <? endforeach; ?>
            <? endif; ?>
            </tbody>
        </table>
    </div>
</div>
