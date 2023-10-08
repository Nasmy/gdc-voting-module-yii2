<?php

namespace app\models;

use yii\mongodb\Exception;
use app\models\Role;

class AuthVoter extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $name;
    public $email;
    public $phoneNo;
    public $voted;
    public $token;
    public $step;
    public $authKey;
    public $accessToken;
    public $roleName;
    public $isSuperadmin = false;

    private static $users = [
        '100' => [
            'id' => '100',
            'email' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'email' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        try {
            $voter = Voter::findOne(['id' => $id]);
            if (null != $voter) {
                return new static([
                    'id' => $voter->id,
                    'name' => $voter->name,
                    'email' => $voter->email,
                    'phoneNo' => $voter->phoneNo,
                    'voted' => $voter->voted,
                    'token' => $voter->token,
                    'step' => $voter->step,
                    'roleName' => $voter->roleName,
                    'isSuperadmin' => false,
                ]);
            }
        } catch (Exception $e) {

        }

        return null;
    }

    /**
     * Finds voter by token
     *
     * @param  string $token
     * @return static|null
     */
    public static function findByToken($hashedToken)
    {
        $voter = Voter::find()->where(['token' => $hashedToken])->notVoted()->one();

        if (null != $voter) {
            return new static([
                'id' => $voter->id,
                'name' => $voter->name,
                'email' => $voter->email,
                'phoneNo' => $voter->phoneNo,
                'voted' => $voter->voted,
                'token' => $voter->token,
                'step' => $voter->step,
                'roleName' => $voter->roleName,
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
