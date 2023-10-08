<?php

namespace app\models;

use Yii;
use app\modules\api\components\ApiStatusMessage;

/**
 * This is the model class for table "Voter".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gender
 * @property string $email
 * @property string $phoneNo
 * @property string $address
 * @property string $media
 * @property string $pressCard
 * @property integer $votingExperience
 * @property integer $voted
 * @property string $votedAt
 * @property string $token
 * @property integer $tokenSent
 * @property string $tokenSentAt
 * @property integer $step
 * @property string $device
 * @property string $platform
 * @property string $platformVersion
 * @property string $browser
 * @property string $browserVersion
 * @property string $roleName
 * @property integer $status
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property Vote[] $votes
 * @property Role $roleName
 * @property User $createdBy
 * @property User $updatedBy
 * @property VoterReminder[] $voterReminders
 */
class Voter extends Base
{
    const ROLE_NAME = 'Voter';

    const TOKEN_SENT_NO = 0;
    const TOKEN_SENT_YES = 1;

    // Voter statuses
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Voter voted
    const VOTED_NO = 0;
    const VOTED_YES = 1;

    // Voted device
    const DEVICE_DESKTOP = 'Desktop';
    const DEVICE_MOBILE = 'Mobile Device';

    public $reminder1;
    public $reminder2;
    public $reminder3;
    public $totalVotes;

    // Validation scenarios
    const SCENARIO_API_CREATE = 'apiCreate';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Voter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'roleName', 'status'], 'required'],

            // API - Voter create
            [['name', 'address', 'phoneNo', 'media', 'email', 'roleName', 'status'], 'required', 'message' => ApiStatusMessage::MISSING_MANDATORY_FIELD, 'on' => [self::SCENARIO_API_CREATE]],

            [['email'], 'email'],
            [['email'], 'unique'],
            [['gender', 'votingExperience', 'voted', 'tokenSent', 'step', 'status', 'createdById', 'updatedById'], 'integer'],
            [['votedAt', 'tokenSentAt', 'createdAt', 'updatedAt'], 'safe'],
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
            'votingExperience' => Yii::t('app', 'Voting Experience'),
            'voted' => Yii::t('app', 'Voted'),
            'votedAt' => Yii::t('app', 'Voted At'),
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
    public function getVotes()
    {
        return $this->hasMany(Vote::className(), ['voterId' => 'id']);
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
    public function getVoterReminders()
    {
        return $this->hasMany(VoterReminder::className(), ['voterId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return VoterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VoterQuery(get_called_class());
    }
}
