<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 22.12.17
 * Time: 16:13
 */

namespace app\traits;

use yii\rbac\Item;
use Yii;

/**
 * Class RbacMigrationTrait
 * @package app\traits
 */
class RbacMigrationTrait
{
    /**
     * @var array
     */
    public $permissionsToAdd = [];

    /**
     * @var array
     */
    public $relationsToAdd = [];

    public $relationsToRemove = [];

    public $permissionsToRemove = [];

    const CONFIG_KEY_PERMISSIONS_TO_ADD = 'permissions_to_add';
    const CONFIG_KEY_RELATIONS_TO_ADD = 'relations_to_add';
    const CONFIG_KEY_PERMISSIONS_TO_REMOVE = 'permissions_to_remove';
    const CONFIG_KEY_RELATIONS_TO_REMOVE = 'relations_to_remove';

    /**
     * RbacMigrationTrait constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if(array_key_exists(self::CONFIG_KEY_PERMISSIONS_TO_ADD, $config)){

            $this->permissionsToAdd = $config[self::CONFIG_KEY_PERMISSIONS_TO_ADD];
        }

        if(array_key_exists(self::CONFIG_KEY_RELATIONS_TO_ADD, $config)){

            $this->relationsToAdd = $config[self::CONFIG_KEY_RELATIONS_TO_ADD];
        }

        if(array_key_exists(self::CONFIG_KEY_PERMISSIONS_TO_REMOVE, $config)){

            $this->permissionsToRemove = $config[self::CONFIG_KEY_PERMISSIONS_TO_REMOVE];
        }

        if(array_key_exists(self::CONFIG_KEY_RELATIONS_TO_REMOVE, $config)){

            $this->relationsToRemove = $config[self::CONFIG_KEY_RELATIONS_TO_REMOVE];
        }

    }

    /**
     * @return bool
     */
    public function processUp(){

        $auth = Yii::$app->authManager;

        if (count($this->permissionsToAdd) > 0) {
            echo 'add new items:' . "\n";

            foreach ($this->permissionsToAdd as $task) {

                echo 'Add permission ' . $task['name'] . "\n";

                $exists = $auth->getPermission($task['name']);

                if ($exists !== null) {
                    echo 'permission ' . $task['name'] . ' already exists, continue' . "\n";
                    continue;
                }

                $permission = $auth->createPermission($task['name']);

                $permission->description = $task['description'];
                $permission->type = $task['type'];

                $auth->add($permission);

                if (array_key_exists('parent', $task)) {


                    $parent = $auth->getPermission($task['parent']);

                    if ($parent === null) {
                        echo 'parent ' . $task['parent'] . ' not found, continue';
                        continue;

                    }
                    if ($auth->addChild($parent, $permission)) {
                        echo ' add relation: parent ' . $parent->name . ', child: ' . $permission->name . "\n";
                    }
                }

            }
        }

        if (count($this->relationsToRemove) > 0) {
            echo 'remove relations:' . "\n";
            foreach ($this->relationsToRemove as $relation) {

                $parent = null;

                if ($relation['parentType'] === Item::TYPE_ROLE) {

                    $parent = $auth->getRole($relation['parent']);

                }

                if ($relation['parentType'] === Item::TYPE_PERMISSION) {

                    $parent = $auth->getPermission($relation['parent']);

                }

                if ($parent === null) {

                    echo 'parent permission ' . $relation['parent'] . ' not found, continues';
                    continue;
                }

                $child = $auth->getPermission($relation['child']);

                if ($child === null) {

                    echo 'child permission ' . $relation['child'] . ' not found, continue';
                    continue;
                }

                if (!$auth->hasChild($parent, $child)) {

                    echo 'pair parent ' . $parent->name . ' - child' . $child->name . ' not exists, nothing to remove, continue';
                    continue;
                }

                $removed = $auth->removeChild($parent, $child);

                if ($removed) {
                    echo 'relation from ' . $parent->name . ' to ' . $child->name . ' removed' . "\n";
                }

            }
        }

        if (count($this->relationsToAdd) > 0) {
            echo 'add new relations:' . "\n";

            foreach ($this->relationsToAdd as $relation) {

                $parent = null;

                if ($relation['parentType'] === Item::TYPE_ROLE) {

                    $parent = $auth->getRole($relation['parent']);

                }

                if ($relation['parentType'] === Item::TYPE_PERMISSION) {

                    $parent = $auth->getPermission($relation['parent']);

                }

                if ($parent === null) {

                    echo 'parent permission ' . $relation['parent'] . ' not found, continue';
                    continue;
                }

                $child = $auth->getPermission($relation['child']);

                if ($child === null) {

                    echo 'child permission ' . $relation['child'] . ' not found, continue';
                    continue;
                }

                if ($auth->hasChild($parent, $child)) {

                    echo 'pair parent ' . $parent->name . ' - child' . $child->name . ' already exists, continue' . "\n";
                    continue;
                }

                $add = $auth->addChild($parent, $child);

                if ($add) {
                    echo 'relation from ' . $parent->name . ' to ' . $child->name . ' added' . "\n";
                }
            }
        }

        if (count($this->permissionsToRemove) > 0) {
            echo 'remove items' . "\n";

            foreach ($this->permissionsToRemove as $item){

                $permission = $auth->getPermission($item['name']);

                if ($permission === null) {
                    echo 'permission ' . $item['name'] . ' not exists, continue' . "\n";
                    continue;
                }

                if ($auth->remove($permission)) {
                    echo 'Removed permission ' . $permission->name . "\n";
                }
            }

        }

        return true;

    }

    /**
     * @return bool
     */
    public function processDown(){

        $auth = Yii::$app->authManager;

        //backward process
        if (count($this->permissionsToRemove) > 0) {
            echo 'remove items' . "\n";

            foreach ($this->permissionsToRemove as $item){

                echo 'Add permission ' . $item['name'] . "\n";

                $exists = $auth->getPermission($item['name']);

                if ($exists !== null) {
                    echo 'permission ' . $item['name'] . ' already exists, continue' . "\n";
                    continue;
                }

                $permission = $auth->createPermission($item['name']);

                $permission->description = $item['description'];
                $permission->type = $item['type'];

                $auth->add($permission);

                if (array_key_exists('parent', $item)) {


                    $parent = $auth->getPermission($item['parent']);

                    if ($parent === null) {
                        echo 'parent ' . $item['parent'] . ' not found, continue';
                        continue;

                    }
                    if ($auth->addChild($parent, $permission)) {
                        echo ' add relation: parent ' . $parent->name . ', child: ' . $permission->name . "\n";
                    }
                }
            }

        }

        foreach ($this->relationsToAdd as $relation) {

            $parent = null;

            if ($relation['parentType'] === Item::TYPE_ROLE) {

                $parent = $auth->getRole($relation['parent']);

            }

            if ($relation['parentType'] === Item::TYPE_PERMISSION) {

                $parent = $auth->getPermission($relation['parent']);

            }

            if ($parent === null) {

                echo 'parent permission ' . $relation['parent'] . ' not found, continue' . "\n";
                continue;
            }

            $child = $auth->getPermission($relation['child']);

            if ($child === null) {

                echo 'child permission ' . $relation['child'] . ' not found, continue' . "\n";
                continue;
            }

            if (!$auth->hasChild($parent, $child)) {

                echo 'pair parent ' . $parent->name . ' - child' . $child->name . ' not exists, nothing to remove, continue';
                continue;
            }

            $removed = $auth->removeChild($parent, $child);

            if ($removed) {
                echo 'relation from ' . $parent->name . ' to ' . $child->name . ' removed' . "\n";
            }
        }

        foreach ($this->relationsToRemove as $relation) {

            $parent = null;

            if ($relation['parentType'] === Item::TYPE_ROLE) {

                $parent = $auth->getRole($relation['parent']);

            }

            if ($relation['parentType'] === Item::TYPE_PERMISSION) {

                $parent = $auth->getPermission($relation['parent']);

            }

            if ($parent === null) {

                echo 'parent permission ' . $relation['parent'] . ' not found, continue';
                continue;
            }

            $child = $auth->getPermission($relation['child']);

            if ($child === null) {

                echo 'child permission ' . $relation['child'] . ' not found, continue';
                continue;
            }

            if ($auth->hasChild($parent, $child)) {

                echo 'pair parent ' . $parent->name . ' - child' . $child->name . ' exists, continue';
                continue;
            }

            $add = $auth->addChild($parent, $child);

            if ($add) {
                echo 'relation from ' . $parent->name . ' to ' . $child->name . ' added' . "\n";
            }

        }

        foreach ($this->permissionsToAdd as $task) {

            $permission = $auth->getPermission($task['name']);

            if ($permission === null) {
                $permission = $auth->getRole($task['name']);
            }

            if ($permission === null) {
                echo 'permission ' . $task['name'] . ' not exists, continue' . "\n";
                continue;
            }

            if ($auth->remove($permission)) {
                echo 'Removed permission ' . $permission->name . "\n";
            }

        }

        return true;
    }
}