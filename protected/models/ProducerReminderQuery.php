<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VoterReminder]].
 *
 * @see VoterReminder
 */
class VoterReminderQuery extends \yii\db\ActiveQuery
{
    /*
    public function active()
    {
        return $this->andWhere('[[status]] = 1');
    }
    */
	
	
	public function reminderNumber($remindNo){
        return 	$this->andWhere('[[remindNo]] = '.$remindNo); //	remindNo
    }

    /**
     * @inheritdoc
     * @return VoterReminder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VoterReminder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
