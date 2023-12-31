<?php

namespace app\components;

use Yii;
use app\models\RolePermission;

class WebUser extends \yii\web\User
{

    /**
     * Check single permission
     * @param string $permissionName name of ther permission. E.g.: User.Create > Controller.Action
     * @param mixed $params
     * @param boolean $allowCaching
     * @return boolean true or false
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if (Yii::$app->user->identity->isSuperadmin) {
            return true;
        }

        try {
            $rolePermission = RolePermission::findOne(['roleName' => Yii::$app->user->identity->roleName, 'permissionName' => $permissionName]);
            if ($rolePermission) {
                return true;
            }
        } catch (Exception $e) {

        }

        return false;
    }

    /**
     * Check whether atleast one permission exists
     * @param array $permissionNames array of permissions
     * @param mixed $params
     * @param boolean $allowCaching
     * @return boolean true or false
     */
    public function canList($permissionNames, $params = [], $allowCaching = true)
    {
        foreach ($permissionNames as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }

        return false;
    }
}