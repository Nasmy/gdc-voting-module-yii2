<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Vote1]].
 *
 * @see Vote1
 */
class VoteQuery extends \yii\db\ActiveQuery
{
    /*
    public function active()
    {
        return $this->andWhere('[[status]] = 1');
    }
    */

    /**
     * @inheritdoc
     * @return Vote1[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Vote1|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
