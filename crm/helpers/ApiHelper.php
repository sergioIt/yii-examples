<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12.09.18
 * Time: 16:46
 */

namespace app\helpers;

use app\helpers\param\ApiParam;
use Yii;

/**
 * Class ApiHelper
 * @package app\helpers
 */
class ApiHelper
{
    const TOKEN_PARAM = 'token';

    public $errMessage;

    const STATUS_OK = 'ok';
    const STATUS_ERROR = 'error';
    const IDS_KEY = 'ids';
    const TOKEN_HEADER = 'X-Auth-Token';

    /**
     * @return bool
     */
    public function checkTokenHeader(){

        $headers = Yii::$app->request->headers;

        $token = $headers->get(self::TOKEN_HEADER);


        if($token !== ApiParam::getToken()){

            $this->errMessage = 'token header missed or token mismatch';

            return false;
        }


        return true;

    }
}
