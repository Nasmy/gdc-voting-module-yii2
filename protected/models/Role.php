<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Role".
 *
 * @property string $name
 * @property string $description
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property RolePermission[] $rolePermissions
 * @property Permission[] $permissionNames
 * @property User[] $users
 * @property Voter[] $voters
 */
class Role extends Base
{
    // Roles
    const SUPER_ADMIN = 'SuperAdmin';
    const SYSTEM_ADMIN = 'SystemAdmin';
    const VOTER = 'Voter';

    // To store user selected permissions
    public $selPermissions = [];
    // To store db permissions
    public $dbPermissions = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['selPermissions'], 'required', 'message' => Yii::t('app', 'Please select at least one permission.')],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z][A-Za-z0-9_.]*$/'],
            [['name'], 'unique'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt', 'selPermissions', 'dbPermissions'], 'safe'],
            [['createdById', 'updatedById'], 'integer'],
            [['name'], 'string', 'max' => 64],
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
        return $this->hasMany(RolePermission::className(), ['roleName' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionNames()
    {
        return $this->hasMany(Permission::className(), ['name' => 'permissionName'])->viaTable('RolePermission', ['roleName' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['roleName' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoters()
    {
        return $this->hasMany(Voter::className(), ['roleName' => 'name']);
    }

    /**
     * @inheritdoc
     * @return RoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoleQuery(get_called_class());
    }
}
