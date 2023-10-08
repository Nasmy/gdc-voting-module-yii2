<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class ProducerLoginForm extends Model
{
    public $token;
    public $rememberMe = true;
    public $captcha;
    private $_producer = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('app', 'Token'),
            'captcha' => Yii::t('app', 'Captcha'),
        ];
    }

    /**
     * Validates the token.
     * This method serves as the inline validation for token.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            } else if (!$user->status) {
                $this->addError($attribute, Yii::t('app', 'Your account is inactive.'));
            }
        }
    }

    /**
     * Logs in a user using the provided token post method
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if($this->getProducerByToken()){
                return Yii::$app->user->login($this->getProducerByToken(), $this->rememberMe ? 3600 * 24 * 1 : 0);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Finds voter by [[token]]
     * @return Voter|null
     */
    public function getProducerByToken()
    {
        if (false === $this->_producer) {

            //Tokens are hashed by core PHP func with static salt key
            $hashedToken = $this->getHashedToken();
			
			//echo "\n<br>this:";
			//print_r($this);
			
			//if ($this->token == '4225X5PKSO') {
			//	echo "\n<br>token 3: " . $this->token;
			//	echo "\n<br>hashedToken: " . $hashedToken;
			//}
			
			//echo "\n<br />hashedToken: " . $hashedToken;
            $this->_producer = AuthProducer::findByToken($hashedToken);
        }
        return $this->_producer;
    }

    /**
     * @return bool|string
     */
    public function getHashedToken(){

        return password_hash($this->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
    }
}
