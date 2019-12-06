<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 23.03.2018, 17:48
 */

namespace app\components;

/**
 * Class DbManager
 * @package app\components
 */
class DbManager extends \yii\rbac\DbManager
{
    /**
     * @var array
     */
    private $_assignments = [];

    /**
     * @param int|string $userId
     * @return mixed
     */
    public function getAssignments($userId)
    {
        // Avoid multiple queries per request
        if(!isset($this->_assignments[$userId]))
            $this->_assignments[$userId] = parent::getAssignments($userId);
        return $this->_assignments[$userId];
    }
}
