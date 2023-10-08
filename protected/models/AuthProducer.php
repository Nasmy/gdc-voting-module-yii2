<?php

namespace app\models;

use yii\mongodb\Exception;
use app\models\Role;

class AuthProducer extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $name;
    public $email;
    public $phoneNo;
    public $registered;
    public $token;
    public $step;
    public $authKey;
    public $accessToken;
    public $roleName;
    public $isSuperadmin = false;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        try {
            $producer = Producer::findOne(['id' => $id]);
            if (null != $producer) {
                return new static([
                    'id' => $producer->id,
                    'name' => $producer->name,
                    'email' => $producer->email,
                    'phoneNo' => $producer->phoneNo,
                    'registered' => $producer->registered,
                    'token' => $producer->token,
                    'step' => $producer->step,
                    'roleName' => $producer->roleName,
                    'isSuperadmin' => false,
                ]);
            }
        } catch (Exception $e) {

        }

        return null;
    }

    /**
     * Finds producer by token
     *
     * @param  string $token
     * @return static|null
     */
    public static function findByToken($hashedToken)
    {
         
        $producer = Producer::find()->where(['token' => $hashedToken])->notRegistered()->one();
        if (null != $producer) {
            return new static([
                'id' => $producer->id,
                'name' => $producer->name,
                'email' => $producer->email,
                'phoneNo' => $producer->phoneNo,
                'registered' => $producer->voted,
                'token' => $producer->token,
                'step' => $producer->step,
                'roleName' => $producer->roleName,
                'isSuperadmin' => false,
            ]);
        }

        return null;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {


    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
