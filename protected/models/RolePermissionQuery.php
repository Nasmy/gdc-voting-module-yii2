<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RolePermission]].
 *
 * @see RolePermission
 */
class RolePermissionQuery extends \yii\db\ActiveQuery
{
    /*
    public function active()
    {
        return $this->andWhere('[[status]] = 1');
    }
    */

    /**
     * @inheritdoc
     * @return RolePermission[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RolePermission|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
