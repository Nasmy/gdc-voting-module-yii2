<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Voter]].
 *
 * @see Voter
 */
class ProducerQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[status]] = 1');
        //return $this->andWhere(['=', 'status', 1]);
    }

    public function registered()
    {
        return $this->andWhere('[[registered]] = 1');
        //return $this->andWhere(['=', 'status', 1]);
    }

    public function notRegistered()
    {
        return $this->andWhere('[[registered]] = 0');
        //return $this->andWhere(['=', 'status', 1]);
    }

    public function tokenSent()
    {
        return $this->andWhere('[[tokenSent]] = 1');
        //return $this->andWhere(['=', 'status', 1]);
    }

    public function notTokenSent()
    {
        return $this->andWhere('[[tokenSent]] = 0');
        //return $this->andWhere(['=', 'status', 1]);
    }

    public function limitQuery($min, $max) {
        return $this->andWhere('[[id]] >'.$min.' and [[id]] <'.$max);
    }

    /**
     * @inheritdoc
     * @return Voter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Voter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /*
    public function withActive()
    {
        return $this->andWhere(['=', 'status', 1]);
    }
    */
}
