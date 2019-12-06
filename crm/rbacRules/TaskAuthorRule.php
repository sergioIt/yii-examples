<?php
/**
 * Rule that checks if task created by current user
 */

namespace app\rbacRules;

use yii\rbac\Rule;

/**
 * Checks if creatorId matches user passed via params
 */
class TaskAuthorRule extends Rule
{
    public $name = 'isTaskAuthor';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['task']) ? $params['task']->creator_id == $user : false;
    }
}