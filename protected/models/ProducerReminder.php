<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ProducerReminder".
 *
 * @property integer $id
 * @property integer $voterId
 * @property integer $remindNo
 * @property string $remindAt
 *
 * @property Producer $voter
 */
class ProducerReminder extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProducerReminder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['producerId', 'remindNo'], 'required'],
            [['producerId', 'remindNo'], 'integer'],
            [['remindAt'], 'safe'],
            [['producerId'], 'exist', 'skipOnError' => true, 'targetClass' => Producer::className(), 'targetAttribute' => ['producerId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'producerId' => Yii::t('app', 'Producer'),
            'remindNo' => Yii::t('app', 'Remind No'),
            'remindAt' => Yii::t('app', 'Remind At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducer()
    {
        return $this->hasOne(Producer::className(), ['id' => 'producerId']);
    }

    /**
     * @inheritdoc
     * @return ProducerReminderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProducerReminderQuery(get_called_class());
    }

}
