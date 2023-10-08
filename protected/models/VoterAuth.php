<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * VoterAuth is the model used to authenticate voter.
 */
class VoterAuth extends Model
{
    public $token;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['token'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('app', 'Token'),
        ];
    }

    /**
     * Decode the token
     */
    public function decodeToken()
    {
        $this->token = urldecode($this->token);
        $this->token = base64_decode($this->token);
        $this->token = substr($this->token, 0, 10);
    }

    /**
     * Hash the token
     */
    public function hashToken()
    {
        $this->token = password_hash($this->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
    }

    /**
     * Authenticate voter using token
     * @return mixed
     */
    public function authenticate()
    {
        $model = null;

        $this->decodeToken();
        $this->hashToken();
        $model = Voter::find()->where(['token' => $this->token])->notVoted()->one();

        return $model;
    }

    /**
     * Validate specific model
     * @param array $attributes specific attributes to be validated
     * @return boolean $result true of false.
     */
    public function validateModel($attributes = null)
    {
        $result = false;
        $name = get_class($this);

        if ($this->validate($attributes)) {
            $result = true;
            Yii::$app->appLog->writeLog("{$name} record validation success.");
        } else {
            Yii::$app->appLog->writeLog("{$name} record validation failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
        }

        return $result;
    }
}
