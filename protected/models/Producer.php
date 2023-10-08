<?php

namespace app\models;

use Yii;
use app\modules\api\components\ApiStatusMessage;


class Producer extends Base
{
    const ROLE_NAME = 'Producer';

    const TOKEN_SENT_NO = 0;
    const TOKEN_SENT_YES = 1;

    // Producer statuses
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Producer voted
    const VOTED_NO = 0;
    const VOTED_YES = 1;

    // Register device
    const DEVICE_DESKTOP = 'Desktop';
    const DEVICE_MOBILE = 'Mobile Device';

    public $reminder1;
    public $reminder2;
    public $reminder3;
    public $totalRegister;

    // Validation scenarios
    const SCENARIO_API_CREATE = 'apiCreate';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'producer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'roleName', 'status'], 'required'],

            // API - Producer create
            [['name', 'phoneNo', 'email', 'roleName', 'status'], 'required', 'message' => ApiStatusMessage::MISSING_MANDATORY_FIELD, 'on' => [self::SCENARIO_API_CREATE]],

            [['email'], 'email'],
            [['email'], 'unique'],
            [['gender', 'registeringExperience', 'registered', 'tokenSent', 'step', 'status', 'createdById', 'updatedById'], 'integer'],
            [['registeredAt', 'tokenSentAt', 'createdAt', 'updatedAt'], 'safe'],
            [['name', 'media', 'pressCard'], 'string', 'max' => 128],
            [['email', 'roleName'], 'string', 'max' => 64],
            [['phoneNo', 'device', 'platform', 'browser'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 250],
            [['token'], 'string', 'max' => 256],
            [['platformVersion', 'browserVersion'], 'string', 'max' => 16],
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
            'name' => Yii::t('app', 'Name'),
            'gender' => Yii::t('app', 'Gender'),
            'email' => Yii::t('app', 'Email'),
            'phoneNo' => Yii::t('app', 'Phone No'),
            'address' => Yii::t('app', 'Address'),
            'media' => Yii::t('app', 'Media'),
            'pressCard' => Yii::t('app', 'Press Card'),
            'registeringExperience' => Yii::t('app', 'Registering Experience'),
            'registered' => Yii::t('app', 'Registered'),
            'registeredAt' => Yii::t('app', 'Registered At'),
            'token' => Yii::t('app', 'Token'),
            'tokenSent' => Yii::t('app', 'Token Sent'),
            'tokenSentAt' => Yii::t('app', 'Token Sent At'),
            'step' => Yii::t('app', 'Step'),
            'device' => Yii::t('app', 'Device'),
            'platform' => Yii::t('app', 'Platform'),
            'platformVersion' => Yii::t('app', 'Platform Version'),
            'browser' => Yii::t('app', 'Browser'),
            'browserVersion' => Yii::t('app', 'Browser Version'),
            'roleName' => Yii::t('app', 'Role Name'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
            'reminder1' => Yii::t('app', '1st Reminder'),
            'reminder2' => Yii::t('app', '2nd Reminder'),
            'reminder3' => Yii::t('app', '3rd Reminder'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegister()
    {
        return $this->hasMany(Register::className(), ['producerId' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getProducerReminders()
    {
        return $this->hasMany(ProducerReminder::className(), ['producerId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProducerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProducerQuery(get_called_class());
    }

}
