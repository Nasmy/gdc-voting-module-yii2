<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RolePermission".
 *
 * @property string $roleName
 * @property string $permissionName
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property Permission $permissionName
 * @property Role $roleName
 * @property User $createdBy
 * @property User $updatedBy
 */
class RolePermission extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RolePermission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleName', 'permissionName'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['createdById', 'updatedById'], 'integer'],
            [['roleName', 'permissionName'], 'string', 'max' => 64],
            [['permissionName'], 'exist', 'skipOnError' => true, 'targetClass' => Permission::className(), 'targetAttribute' => ['permissionName' => 'name']],
            [['roleName'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['roleName' => 'name']],
            [['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdById' => 'id']],
            [['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updatedById' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'roleName' => Yii::t('app', 'Role Name'),
            'permissionName' => Yii::t('app', 'Permission Name'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionName()
    {
        return $this->hasOne(Permission::className(), ['name' => 'permissionName']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleName()
    {
        return $this->hasOne(Role::className(), ['name' => 'roleName']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createdById']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updatedById']);
    }

    /**
     * @inheritdoc
     * @return RolePermissionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RolePermissionQuery(get_called_class());
    }
}
