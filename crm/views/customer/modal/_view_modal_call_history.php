<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 04.06.18
 * Time: 17:13
 *
 *  @var $dataProvider \yii\data\ArrayDataProvider
 * @var $searchModel \app\models\CallListSearch
 *  @var $users [] array
 * @var $customers [] array
 *
 * @var \app\models\Customers $customer
 */

use app\components\Pjax;

Pjax::begin([
'id'              => 'pjax-customer-call-history-' . $customer->id,
'formSelector'    => '#gw-customer-call-history-' . $customer->id . ' form',
'timeout'         => false,
'enablePushState' => false,
]);
?>

<?= $this->render('/call/_list_grid', [

    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'users' => $users,
    'customer' => $customer
]);

Pjax::end();
