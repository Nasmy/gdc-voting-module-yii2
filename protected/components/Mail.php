<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\View;
use app\components\RestClient;

class Mail extends Component
{

    public $emailTemplatePath = '@app/views/email-template/notificationTemplate';
    public $preseeEmailTemplatePath = '@app/views/email-template/presseTemplate';
    public $apiEndPoint;
    public $apiUsername;
    public $apiPassword;
    public $fromEmail;
    public $fromName;
    public $language;

    public function __construct()
    {
        $this->language = Yii::$app->params['defaultLanguage'];
        $this->fromEmail = Yii::$app->params['supportEmail'];
        $this->fromName = Yii::$app->params['productName'];
        $this->apiEndPoint = Yii::$app->params['mailgun']['apiEndPoint'];
        $this->apiUsername = Yii::$app->params['mailgun']['apiUsername'];
        $this->apiPassword = Yii::$app->params['mailgun']['apiPassword'];
    }

    public function sendVoterPrese($email, $name, $gender, $url, $token) {

        //echo "\n<br />gender:" . $gender;
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Hello', null, $this->language);
        }
        $subject = 'Communiqué de Presse / Mardi 5 Février 2019 / Globes de Cristal 2019';
//        $subject = Yii::t('mail', 'Communiqué de Presse / Cérémonie des Globes de Cristal 2019', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>Kindly participate to 2016 edtion of LES GLOBES DE CRISTAL voting event.</p>
<p>Please remember the voting event will be closed in one month.</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);

    }


 

    /**
     * Send voter token email
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendVoterTokenEmail($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;
        $subject = Yii::t('mail', 'Invitation to Vote at LES GLOBES DE CRISTAL', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>Kindly participate to 2016 edtion of LES GLOBES DE CRISTAL voting event.</p>
<p>Please remember the voting event will be closed in one month.</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send first voting reminder to voter
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendFirstReminderToVoter($email, $name, $gender, $url, $token)
    {
		//echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
<p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send second voting reminder to voter
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendSecondReminderToVoter($email, $name, $gender, $url, $token)
    {
		//echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
		$message = Yii::t('mail', '{gender} {name},
<p></p>
<p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
<p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

		/*
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>The 11th ceremony of the Crystal Globe voting closes in a few days.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);
		*/
        return $this->send($email, $subject, $message);
    }

	/**
     * Send third voting reminder to voter
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendThirdReminderToVoter($email, $name, $gender, $url, $token)
    {
		//echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
		$message = Yii::t('mail', '{gender} {name},
<p></p>
<p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
<p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

		/*
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>The 11th ceremony of the Crystal Globe voting closes in a few days.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);
		*/
        return $this->send($email, $subject, $message);
    }

	/**
     * Send third voting reminder to voter
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendForthReminderToVoter($email, $name, $gender, $url, $token)
    {
		//echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
		$message = Yii::t('mail', '{gender} {name},
<p></p>
<p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
<p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

		/*
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>The 11th ceremony of the Crystal Globe voting closes in a few days.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);
		*/
        return $this->send($email, $subject, $message);
    }

    /**
     * Send final voting reminder mail to voter
     * @param string $email voter email
     * @param string $name voter name
	 * @param string $gender voter gender
	 * @param string $gender voter specific voting url
	 * @param string $gender voter token
     * @return boolean email send response (true or false)
     */
    public function sendFinalReminderToVoter($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        //echo "\n<br>gender: " . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
		$message = Yii::t('mail', '{gender} {name},
<p></p>
<p>The 11th ceremony of the Crystal Globe voting closes tonight.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>You can login to vote by clicking below link:</p>
<p>Link: {url}</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    public function sendApologyReminderToVoter($email, $name, $gender)
    {
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>The 11th ceremony of the Crystal Globe voting closes tonight.</p>
<p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
<p>$nbsp;- Best Film</p>
<p>$nbsp;- Best Actress</p>
<p>$nbsp;- Best Actor</p>
<p>$nbsp;- Best Theatre Piece</p>
<p>Sincerely,</p>', ['name' => $name, 'gender' => $gender], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * @param $email
     * @param $name
     * @param $votedSummery
     * @return bool
     */
    public function sendVotingSummary($email, $name, $gender, $votedSummery)
    {
        $subject = Yii::t('mail', 'Your voting summary at '); //  . Yii::$app->params['productName'];

        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }

        $message = Yii::t('mail', '{gender} {name},
<p></p>
<p>Thank you for completing the voting process.</p>
<p>Please check the following vote summary:</p>', ['name' => $name, 'gender' => $gender]);
        foreach ($votedSummery as $votedSummeryItem) {
            $message.= '<p><strong>' . $votedSummeryItem->name . ' : ' . $votedSummeryItem->value . '</strong></p>';
        }
        $message .= Yii::t('mail', '<p>Sincerely,</p>');
        //echo "\n<br />subject: " . $subject;
        //echo "\n<br />message: " . $message;
        //exit;
        return $this->send($email, $subject, $message);
    }

    /**
     * @param $email
     * @param $name
     * @param $votingResults
     * @return bool
     */
    public function sendVotingResults($email, $name, $votingResults)
    {
        //TODO
        $mailArr = $email.',logeisharooban@gmail.com,steve@keeneye.solutions';

        $subject = Yii::t('mail', 'Voting results at ');
        $message = Yii::t('mail', 'Dear {name},<p>Please check the following voting results.</p>', ['name' => $name]);
        $message.= $votingResults;
        $message .= Yii::t('mail', '<p>Sincerely,</p>');

        return $this->send($mailArr, $subject, $message);
    }

    /**
     * Send forgot password email
     * @param string $email Recipient email
     * @param string $link Password reset link
     * @return boolean true/false
     */
    public function sendForgotPasswordEmail($email, $link)
    {
        $subject = Yii::t('mail', 'Reset password', [], $this->language);
        $message = Yii::t('mail', 'Impact,
<p></p>
<p>Please, click on the following link to reset your password {link}
<br>Thank you.</p>', [
                    'link' => $link
                        ], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send password reset email
     * @param string $email Recipient email
     * @param string $name Recipient name
     * @return boolean true/false
     */
    public function sendPasswordResetEmail($email, $name)
    {
        $subject = Yii::t('mail', 'Reset password', [], $this->language);
        $message = Yii::t('mail', 'Impact {name},
<p></p>
<p>Your password was successfully changed.
<br>Thank you.</p>', [
                    'name' => $name
                        ], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send voter token email
     * @param string $email voter email
     * @param string $name voter name
     * @return boolean mail send response - true/false
     */
    public function sendCustomVoterTokenEmail($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;
        $subject = Yii::t('mail', 'Important: Modification of vote/Globes de Cristal', null, $this->language);
        $message = Yii::t('mail', '<p>{gender} {name},</p>
<p>Following the replacement, in the list of nominees, of the "Phantom of the Opera adaptation of the novel by Gaston Leroux, produced by Stage Entertainment in collaboration with The Really Useful Group, directed by Harold Prince (Theater Mogador) by" Timéo " Produced by Jérémie de Lacharrière, directed by Alex Goude (Casino de Paris), we invite you to vote exclusively for the category "Best Musical Comedy".</p>
<p>To vote, just click on the link below:</p>
<p><a href="{url}" target="_blank">Go to the vote</a></p>
<p>{url}</p>
<p>We apologize for the inconvenience and thank you for your participation.</p><br>
<p>Note: for more information, we invite you to visit the "Press" section, "Press Release" section of our website. <a href="http://www.globesdecristal.com" target="_blank">www.globesdecristal.com</a></p>
<p>Sincerely,</p><br>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
    * send press release to jurnalist
    **/

    public function sendPreseeWinnerEmail($email)
    {
        $subject = Yii::t('mail', 'The winners of the Globes de Cristal 2018', null, $this->language);
        $message = Yii::t('mail', '
<p></p>
<p>The winners of the Globes de Cristal 2018.
<br>Thank you.</p>', $this->language);

        return $this->send($email,$subject,$message);
    }

    /**
     * Send email
     * @param string $toEmail Recipient email
     * @param string $subject Email subject
     * @param string $content Email body
     * @param string $fromEmail Sender email
     * @param string $fromName Sender name
     * @return boolean true/false
     */
    public function send($toEmail, $subject, $content, $fromEmail = null, $fromName = null)
    {
        $view = new View();
        Yii::$app->language = $this->language;

        if (null == $fromEmail) {
            $fromEmail = $this->fromEmail;
        }

        if (null == $fromName) {
            $fromName = $this->fromName;
        }

        $restClient = new RestClient($this->apiUsername, $this->apiPassword, $this->apiEndPoint);
        //echo "\n<br>restClient: ";
        //print_r($restClient);

        $restClient->sendRequest('messages', [
            'from' => "$fromName <$fromEmail>",
            'to' => $toEmail,
            'subject' => $subject,
            'html' => $view->render($this->emailTemplatePath, ['content' => $content], true)
                ], 'POST');
        //echo "\n<br>restClient: ";
        //print_r($restClient);

        $res = $restClient->response;

        Yii::$app->appLog->writeLog('Email API response.', ['response' => $res]);

        if (!empty($res)) {
            $res = json_decode($res);
            if (strstr(@$res->message, 'Queued')) {
                Yii::$app->appLog->writeLog('Email sent.', ['from' => $fromEmail, 'to' => $toEmail]);
                return true;
            }
        }

        Yii::$app->appLog->writeLog('Email sending failed.', ['from' => $fromEmail, 'to' => $toEmail]);

        return false;
    }

    /**
     * Send email
     * @param string $toEmail Recipient email
     * @param string $subject Email subject
     * @param string $content Email body
     * @param string $fromEmail Sender email
     * @param string $fromName Sender name
     * @return boolean true/false
     */
    /*
      public function send($toEmail, $subject, $content, $fromEmail = null, $fromName = null)
      {
      Yii::$app->language = $this->language;

      if (null == $fromEmail) {
      $fromEmail = $this->fromEmail;
      }

      if (null == $fromName) {
      $fromName = $this->fromName;
      }

      $error = '';
      $response = false;
      try {
      $response = Yii::$app->mailer
      ->compose($this->emailTemplatePath, ['content' => $content])
      ->setFrom([$fromEmail => $fromName])
      ->setTo($toEmail)
      ->setSubject($subject)
      ->send();
      } catch (\Exception $e) {
      $error = $e->getMessage();
      }

      if ($response) {
      Yii::$app->appLog->writeLog('Email sent.', ['from' => $fromEmail, 'to' => $toEmail]);
      return true;
      }

      Yii::$app->appLog->writeLog('Email sending failed.', ['from' => $fromEmail, 'to' => $toEmail, 'error' => $error]);
      return false;
      }
     */

       public function sendProducerPrese($email, $name, $gender, $url, $token) 
{

        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Hello', null, $this->language);
        }
        $subject = 'Communiqué de Presse / Mardi 5 Février 2019 / Globes de Cristal 2019';
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>Kindly participate to 2016 edtion of LES GLOBES DE CRISTAL registering event.</p>
        <p>Please remember the registering event will be closed in one month.</p>
        <p>You can login to register by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);

    }


     /**
     * Send producer token email
     * @param string $email producer email
     * @param string $name producer name
     * @return boolean mail send response - true/false
     */
    public function sendProducerTokenEmail($email, $name, $gender, $url, $token)
    {

        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        $subject = Yii::t('mail', 'Invitation to Register at LES GLOBES DE CRISTAL', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>Kindly participate to 2016 edtion of LES GLOBES DE CRISTAL registering event.</p>
        <p>Please remember the voting event will be closed in one month.</p>
        <p>You can login to register by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send first voting reminder to producer
     * @param string $email producer email
     * @param string $name producer name
     * @return boolean mail send response - true/false
     */
    public function sendFirstReminderToProducer($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Register', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
        <p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>You can login to vote by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send second voting reminder to producer
     * @param string $email producer email
     * @param string $name producer name
     * @return boolean mail send response - true/false
     */
    public function sendSecondReminderToProducer($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Register', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
        <p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>You can login to vote by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * Send third voting reminder to producer
     * @param string $email producer email
     * @param string $name producer name
     * @return boolean mail send response - true/false
     */
    public function sendThirdReminderToProducer($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2019/Register', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>You have not participated in the register to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
        <p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>You can login to register by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);


        return $this->send($email, $subject, $message);
    }

    /**
     * Send third voting reminder to producer
     * @param string $email producer email
     * @param string $name producer name
     * @return boolean mail send response - true/false
     */
    public function sendForthReminderToProducer($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>You have not participated in the vote to select the winners of the 11th ceremony of LES GLOBES DE CRISTAL.</p>
        <p>The French press awards rewards LES GLOBES DE CRISTAL in the disciplines of Arts and Culture for the year 2016.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>You can login to vote by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);


        return $this->send($email, $subject, $message);
    }

    /**
     * Send final voting reminder mail to producer
     * @param string $email producer email
     * @param string $name producer name
     * @param string $gender producer gender
     * @param string $gender producer specific voting url
     * @param string $gender producer token
     * @return boolean email send response (true or false)
     */
    public function sendFinalReminderToProducer($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }
        //echo "\n<br>gender: " . $gender;

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2019/Register', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>The 11th ceremony of the Crystal Globe voting closes tonight.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>You can login to vote by clicking below link:</p>
        <p>Link: {url}</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }

    public function sendApologyReminderToProducer($email, $name, $gender)
    {
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }

        $subject = Yii::t('mail', 'LES GLOBES DE CRISTAL ceremony in 2018/Vote', null, $this->language);
        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>The 11th ceremony of the Crystal Globe voting closes tonight.</p>
        <p>The Jury, chaired by Ms Catherine Deneuve, has established a list of five nominated in fourteen categories representative of each cultural area:</p>
        <p>$nbsp;- Best Film</p>
        <p>$nbsp;- Best Actress</p>
        <p>$nbsp;- Best Actor</p>
        <p>$nbsp;- Best Theatre Piece</p>
        <p>Sincerely,</p>', ['name' => $name, 'gender' => $gender], $this->language);

        return $this->send($email, $subject, $message);
    }

    /**
     * @param $email
     * @param $name
     * @param $votedSummery
     * @return bool
     */
    public function sendRegisteringSummary($email, $name, $gender, $registeredSummery)
    {
        $subject = Yii::t('mail', 'Your registering summary at '); 
        //  . Yii::$app->params['productName'];

        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } elseif (2 == $gender) {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Bonjour', null, $this->language);
        }

        $message = Yii::t('mail', '{gender} {name},
        <p></p>
        <p>Thank you for completing the voting process.</p>
        <p>Please check the following vote summary:</p>', ['name' => $name, 'gender' => $gender]);
        foreach ($registeredSummery as $registeredSummeryItem) {
            $message.= '<p><strong>' . $registeredSummeryItem->name . ' : ' . $registeredSummeryItem->value . '</strong></p>';
        }
        $message .= Yii::t('mail', '<p>Sincerely,</p>');
        //echo "\n<br />subject: " . $subject;
        //echo "\n<br />message: " . $message;
        //exit;
        return $this->send($email, $subject, $message);
    }

    /**
     * @param $email
     * @param $name
     * @param $votingResults
     * @return bool
     */
    public function sendRegisteringResults($email, $name, $registeringResults)
    {
        //TODO
        $mailArr = $email.',logeisharooban@gmail.com,steve@keeneye.solutions';

        $subject = Yii::t('mail', 'Registering results at ');
        $message = Yii::t('mail', 'Dear {name},<p>Please check the following registering results.</p>', ['name' => $name]);
        $message.= $registeringResults;
        $message .= Yii::t('mail', '<p>Sincerely,</p>');

        return $this->send($mailArr, $subject, $message);
    }

    public function sendCustomProducserTokenEmail($email, $name, $gender, $url, $token)
    {
        //echo "\n<br />language:" . $this->language;
        if (1 == $gender) {
            $gender = Yii::t('mail', 'Male', null, $this->language);
        } else {
            $gender = Yii::t('mail', 'Female', null, $this->language);
        }
        //echo "\n<br />gender:" . $gender;
        $subject = Yii::t('mail', 'Important: Modification of register/Globes de Cristal', null, $this->language);
        $message = Yii::t('mail', '<p>{gender} {name},</p>
<p>Following the replacement, in the list of nominees, of the "Phantom of the Opera adaptation of the novel by Gaston Leroux, produced by Stage Entertainment in collaboration with The Really Useful Group, directed by Harold Prince (Theater Mogador) by" Timéo " Produced by Jérémie de Lacharrière, directed by Alex Goude (Casino de Paris), we invite you to vote exclusively for the category "Best Musical Comedy".</p>
<p>To vote, just click on the link below:</p>
<p><a href="{url}" target="_blank">Go to the vote</a></p>
<p>{url}</p>
<p>We apologize for the inconvenience and thank you for your participation.</p><br>
<p>Note: for more information, we invite you to visit the "Press" section, "Press Release" section of our website. <a href="http://www.globesdecristal.com" target="_blank">www.globesdecristal.com</a></p>
<p>Sincerely,</p><br>', ['name' => $name, 'gender' => $gender, 'url' => $url], $this->language);

        return $this->send($email, $subject, $message);
    }



}
