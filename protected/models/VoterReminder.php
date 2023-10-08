<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "VoterReminder".
 *
 * @property integer $id
 * @property integer $voterId
 * @property integer $remindNo
 * @property string $remindAt
 *
 * @property Voter $voter
 */
class VoterReminder extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VoterReminder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voterId', 'remindNo'], 'required'],
            [['voterId', 'remindNo'], 'integer'],
            [['remindAt'], 'safe'],
            [['voterId'], 'exist', 'skipOnError' => true, 'targetClass' => Voter::className(), 'targetAttribute' => ['voterId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'voterId' => Yii::t('app', 'Voter'),
            'remindNo' => Yii::t('app', 'Remind No'),
            'remindAt' => Yii::t('app', 'Remind At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoter()
    {
        return $this->hasOne(Voter::className(), ['id' => 'voterId']);
    }

    /**
     * @inheritdoc
     * @return VoterReminderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VoterReminderQuery(get_called_class());
    }

}
