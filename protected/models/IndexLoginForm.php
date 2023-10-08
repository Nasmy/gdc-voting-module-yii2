<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class IndexLoginForm extends Model
{
    public $token;
    public $rememberMe = true;
    public $captcha;
    private $_voter = false;

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
			
            if($this->getVoterByToken()){
				
                return Yii::$app->user->login($this->getVoterByToken(), $this->rememberMe ? 3600 * 24 * 1 : 0);
				
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
    public function getVoterByToken()
    {
        if (false === $this->_voter) {

            // Tokens are hashed by core PHP func with static salt key
            $hashedToken = $this->getHashedToken();
			
			echo "\n<br />hashedToken: " . $hashedToken;
            
			$this->_voter = AuthVoter::findByToken($hashedToken);
			
			echo "\n<br />_voter: ";
			print_r($this->_voter);
			
        }
        return $this->_voter;
    }

    /**
     * @return bool|string
     */
    public function getHashedToken(){

        return password_hash($this->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
    }
}
