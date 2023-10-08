<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Register1]].
 *
 * @see Register1
 */
class RegisterQuery extends \yii\db\ActiveQuery
{


    /**
     * @inheritdoc
     * @return Register1[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Register1|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
