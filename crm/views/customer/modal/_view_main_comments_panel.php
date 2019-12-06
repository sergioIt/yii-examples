<?php
/**
 * @var $card app\models\Card
 * @var $dateTime \app\traits\DateTimeTrait
 */

$displayComments = (count($card->comments) === 0) ?  'none' : 'block';
$timeZone = $card->ownerCountry->timezone;
$dateTime = new \app\traits\DateTimeTrait();

use yii\helpers\Html;
?>
<div class="panel panel-default" id="card_view_comments_panel"  style="overflow: auto;">
            <div class="panel-heading">
                <h3 class="panel-title"> <?= Yii::t('app', 'Card.View.Comments') ?></h3>
</div>
<div class="panel-body">
    <div class="row">
        <div class="col-md-8">
            <?
            echo Html::textarea('comment', '', [
                'class' =>'form-control card_comment_text',
                'min-width' => '100%',
                'placeholder' => 'write new comment here'
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= Html::button(Yii::t('app', 'Send comment'), [
                'type' => 'button',
                'class' => 'btn btn-primary btn-xs btn_send_card_comment',
                'data-url' => \Yii::$app->getUrlManager()->createUrl('card/comment'),
                'data-card_id' => $card->id,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11">

            <table class="card_view_table_comments table table-responsive table-striped font10" style="display: <?=$displayComments?>">
                <thead>
                <tr>
                    <th>date</th>
                    <th>support</th>
                    <th>comment</th>
                </tr>
                </thead>
                <tbody>
                <? if(count($card->comments) > 0): ; ?>
                    <?/**@var $comment \app\models\CardComment */
                    ?>
                    <?foreach ($card->comments as $comment): ?>
                        <tr>
                            <td><?= $dateTime->appendTimeZoneToStringByZone($comment->created, $timeZone); ?></td>
                            <td> <?= $comment->user->login ?> </td>
                            <td> <?= $comment->text ?> </td>
                        </tr>
                    <? endforeach; ?>
                <?endif; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>
</div>
