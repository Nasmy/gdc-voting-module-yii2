<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ActivityLog".
 *
 * @property integer $id
 * @property string $activityAt
 * @property string $username
 * @property integer $userId
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $data
 *
 * @property User $user
 */
class ActivityLog extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ActivityLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityAt'], 'safe'],
            [['userId'], 'integer'],
            [['data'], 'required'],
            [['data'], 'string'],
            [['username', 'module', 'controller', 'action'], 'string', 'max' => 64],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'activityAt' => Yii::t('app', 'Activity At'),
            'username' => Yii::t('app', 'Username'),
            'userId' => Yii::t('app', 'User ID'),
            'module' => Yii::t('app', 'Module'),
            'controller' => Yii::t('app', 'Controller'),
            'action' => Yii::t('app', 'Action'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @inheritdoc
     * @return ActivityLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityLogQuery(get_called_class());
    }

}
