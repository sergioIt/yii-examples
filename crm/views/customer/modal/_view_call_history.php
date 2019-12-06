<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 08.06.18
 * Time: 17:13
 */
use app\components\CustomerModal as Modal;

Modal::begin([
    'id' => 'call_history_modal',
    'header' => '<h3 class="modal-title" id="modal_title">' .
        'Call history for customer #' .
        '<span id="modal_title_customer_id"></span></h3>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">' . Yii::t('app', 'Close') . '</a>',
    'size' => Modal::SIZE_EXTRA_LARGE,
]);
?>
    <div id="call_history_modal_content">


    </div>

<?
Modal::end();
