<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Permission".
 *
 * @property string $name
 * @property string $description
 * @property string $category
 * @property string $subCategory
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property RolePermission[] $rolePermissions
 * @property Role[] $roleNames
 */
class Permission extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['createdById', 'updatedById'], 'integer'],
            [['name', 'category', 'subCategory'], 'string', 'max' => 64],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'category' => Yii::t('app', 'Category'),
            'subCategory' => Yii::t('app', 'Sub Category'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::className(), ['permissionName' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleNames()
    {
        return $this->hasMany(Role::className(), ['name' => 'roleName'])->viaTable('RolePermission', ['permissionName' => 'name']);
    }

    /**
     * @inheritdoc
     * @return PermissionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PermissionQuery(get_called_class());
    }

}
