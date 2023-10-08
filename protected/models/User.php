<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $oldPassword
 * @property string $passwordResetToken
 * @property string $firstName
 * @property string $lastName
 * @property string $profilePicture
 * @property string $phoneNo
 * @property string $timeZone
 * @property string $roleName
 * @property integer $type
 * @property integer $status
 * @property string $lastAccess
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property ActivityLog[] $activityLogs
 * @property Category[] $categories
 * @property Category[] $categories0
 * @property CategoryNominee[] $categoryNominees
 * @property CategoryNominee[] $categoryNominees0
 * @property Configuration[] $configurations
 * @property Configuration[] $configurations0
 * @property Nominee[] $nominees
 * @property Nominee[] $nominees0
 * @property Permission[] $permissions
 * @property Permission[] $permissions0
 * @property Role[] $roles
 * @property Role[] $roles0
 * @property RolePermission[] $rolePermissions
 * @property RolePermission[] $rolePermissions0
 * @property Role $roleName0
 * @property User $createdBy
 * @property User[] $users
 * @property User $updatedBy
 * @property User[] $users0
 * @property Voter[] $voters
 * @property Voter[] $voters0
 */
class User extends Base
{
    // User statuses
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Validation scenarios
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';

    public $formPassword;
    public $confPassword;
    public $newPassword;
	
	private $_statuses = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE =>  'Active',
    ];

    //private $_statuses = [
    //    self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
    //    self::STATUS_ACTIVE =>  Yii::t('app', 'Active'),
    //];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * Returns the user statuses.
     * @return array statuses array.
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'firstName', 'lastName', 'roleName'], 'required'],
            [['type', 'status', 'createdById', 'updatedById'], 'integer'],
            [['lastAccess', 'createdAt', 'updatedAt', 'formPassword', 'confPassword', 'newPassword'], 'safe'],
            [['username', 'email', 'firstName', 'lastName', 'roleName'], 'string', 'max' => 64],
            [['password', 'oldPassword', 'passwordResetToken', 'profilePicture', 'timeZone'], 'string', 'max' => 128],
            [['phoneNo'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],

            [['firstName', 'lastName'], 'match', 'pattern' => '/^[a-zA-Z]+$/'],

            // changePassword
            [['formPassword', 'newPassword', 'confPassword'], 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD]],
            [['formPassword'], 'compare', 'compareValue' => $this->oldPassword, 'operator' => '==', 'type' => 'string', 'on' => [self::SCENARIO_CHANGE_PASSWORD], 'message' => Yii::t('app', 'Incorrect Old Password.')],
            [['confPassword'], 'compare', 'compareAttribute' => 'newPassword', 'operator' => '==', 'type' => 'string', 'on' => [self::SCENARIO_CHANGE_PASSWORD]],
            [['newPassword'], 'checkPasswordStrength', 'params' => ['min' => 7, 'allowEmpty' => false], 'on' => [self::SCENARIO_CHANGE_PASSWORD]],

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
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'oldPassword' => Yii::t('app', 'Old Password'),
            'passwordResetToken' => Yii::t('app', 'Password Reset Token'),
            'formPassword' => Yii::t('app', 'Password'),
            'confPassword' => Yii::t('app', 'Confirm Password'),
            'firstName' => Yii::t('app', 'First Name'),
            'lastName' => Yii::t('app', 'Last Name'),
            'profilePicture' => Yii::t('app', 'Profile Picture'),
            'phoneNo' => Yii::t('app', 'Phone No'),
            'timeZone' => Yii::t('app', 'Time Zone'),
            'roleName' => Yii::t('app', 'Role Name'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'lastAccess' => Yii::t('app', 'Last Access'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityLogs()
    {
        return $this->hasMany(ActivityLog::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories0()
    {
        return $this->hasMany(Category::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryNominees()
    {
        return $this->hasMany(CategoryNominee::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryNominees0()
    {
        return $this->hasMany(CategoryNominee::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfigurations()
    {
        return $this->hasMany(Configuration::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfigurations0()
    {
        return $this->hasMany(Configuration::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominees()
    {
        return $this->hasMany(Nominee::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominees0()
    {
        return $this->hasMany(Nominee::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions0()
    {
        return $this->hasMany(Permission::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles0()
    {
        return $this->hasMany(Role::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions0()
    {
        return $this->hasMany(RolePermission::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleName0()
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['createdById' => 'id']);
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
    public function getUsers0()
    {
        return $this->hasMany(User::className(), ['updatedById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoters()
    {
        return $this->hasMany(Voter::className(), ['createdById' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoters0()
    {
        return $this->hasMany(Voter::className(), ['updatedById' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function getFullNameById($id)
    {
        if (null === $id)
            return null;

        $model = self::findOne($id);

        if (null === $model)
            return null;

        return $model->firstName . ' ' . $model->lastName;
    }

    /**
     * Encrypt password
     * @return string crypt encrypted password.
     */
    public function encryptPassword($password = '')
    {
        $pass = ('' == $password ? $this->password : $password);
		//echo "\n<br />pass = " . $pass;
		//return crypt($pass);
		return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * Generate password to be compared
     * @param string $userInputPassword User input password
     * @param string $dbPassword Password stored in the db
     * @return string generated password to be compared
     */
    public static function getComparingPassword($userInputPassword, $dbPassword)
    {
        return crypt($userInputPassword, $dbPassword);
    }

    /**
     * Check password strength
     * @param string $attribute attribute name
     * @params array $params extra prameters to be passed to validation function
     * @return null
     */
    public function checkPasswordStrength($attribute, $params)
    {
        if ($params['allowEmpty'] && '' == $this->$attribute) {
            return true;
        } else {
            if (preg_match("/^.*(?=.{" . $params['min'] . ",})(?=.*\d)(?=.*[a-zA-Z])(?=.*[-@_#&.]).*$/", $this->$attribute)) {
                return true;
            } else {
                $this->addError($attribute, Yii::t('app', '{attribute} is weak. {attribute} must contain at least {min} characters, at least one letter, at least one number and at least one symbol(-@_#&.).', ['min' => $params['min'], 'attribute' => $this->getAttributeLabel($attribute)]));
            }
        }
    }
}
