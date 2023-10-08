<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\Mail;
use app\components\TokenGenerator;
use app\models\Voter;
use app\models\VoterReminder;

/*
 * This controller send notifications to intended users.
 * Command runs on different time slots.
 */
class NotifyController extends Controller
{
    public $notificationQueue;
    public $notification;
    public $mail;
    public $voter;
    public $voters;
    public $voterReminder;
    public $tokenGenerator;

    public function actionSendVoterToken()
    {
        // exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully');
        $errMsg = Yii::t('app', 'Voter save failed');
        $emailSucMsg = Yii::t('app', 'Email with token sent successfully');
        $emailErrMsg = Yii::t('app', 'Email with token send failed');

		$counter = 0;
        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();
        
		$this->voters = $this->voter->find()->active()->notTokenSent()->all();
		//$this->voters = $this->voter->find()->active()->notVoted()->all();
        //echo "\n<br />voters: ";
		//print_r($this->voters);
        //exit;
		//echo "\n<br />voters - count: " . count($this->voters);
        // exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $voter) {

				$counter++;
				echo "\n<br />counter: " . $counter;
				//echo "\n<br />voter: id: " . $voter->id;
                //echo "\n<br />voter: name: " . $voter->name;
                //echo "\n<br />voter: email: " . $voter->email;
               
                if (5716 != $voter->id) { // 13865 - Yohan, 13866 - Rooban, 13867 - Steve, 13868 - Nasmy
                    continue;
                }
                echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;
				//sleep(1);
                //continue;
				// exit;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                //continue;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
				//exit;

                try {

                    $votesUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendVoterTokenEmail($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent:" . $voter->tokenSent;
                    echo "\n<br />";


                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        //$success = false;
                        $success = $voter->saveModel();
                        //echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }

                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }

					sleep(1);


                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }

				//if ($counter >= 500) {
				//	break;
				//}

            }

        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }

    public function actionSendFirstReminderToVoter()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

		$counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
        // TODO: Necessary messages to Voter Reminder
        // TODO: Insert necessary records to Voter Reminder

        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->all();
		//$this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->limit(15000)->all();
        //echo "\n<br />voters: ";
        //print_r($this->voters);
		//echo "\n<br />voters - count: " . count($this->voters);
        //exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $voter) {

				$counter++;
				$firstReminderSent = null;
				$this->voterReminder = new VoterReminder();

				echo "\n<br />counter: " . $counter;
                //echo "\n<br />voter: id: " . $voter->id;
                //echo "\n<br />voter: name: " . $voter->name;
                //echo "\n<br />voter: email: " . $voter->email;
                //if (5716 != $voter->id) { // 13865 - Yohan, 13866 - Rooban, 5716 - Steve
                //    continue;
                //}
				echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;
				$firstReminderSent = $this->voterReminder->find()->where(['voterId' => $voter->id, 'remindNo' => 1])->count();
				echo "\n<br />firstReminderSent: " . $firstReminderSent;
				if ($firstReminderSent > 0) {
					continue;
				}
				//continue;
				//exit;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                //continue;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
				//continue;
				//exit;

                try {

                    $votesUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendFirstReminderToVoter($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent: " . $voter->tokenSent;

                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $voter->saveModel();
                        echo "\n<br />success: " . $success;
                        if ($success) {
							Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);

							$this->voterReminder->voterId = $voter->id;
							$this->voterReminder->remindNo = 1;
							$success = $this->voterReminder->saveModel();
							echo "\n<br />success: " . $success;
							echo "\n<br />";
							if ($success) {
								Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->voterReminder->attributes]);
							} else {
								Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->voterReminder->errors, 'attributes' => $this->voterReminder->attributes]);
							}

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }

					usleep(100);

                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }

				if ($counter >= 15000) {
					break;
				}
            }
        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }

    public function actionSendSecondReminderToVoter()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
        // TODO: Necessary messages to Voter Reminder
        // TODO: Insert necessary records to Voter Reminder

        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->all();
        //$this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->limit(15000)->all();
        //echo "\n<br />voters: ";
        //print_r($this->voters);
        //exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $voter) {

                $counter++;
                $secondReminderSent = null;
                $this->voterReminder = new VoterReminder();

                echo "\n<br />counter: " . $counter;
                echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;

                //if (5716 != $voter->id) { // 13865 - Yohan, 13866 - Rooban, 13867 - Steve
                    //continue;
                //}

				//echo "\n<br />voter: id: " . $voter->id;
                //echo "\n<br />voter: name: " . $voter->name;
                //echo "\n<br />voter: email: " . $voter->email;

                $secondReminderSent = $this->voterReminder->find()->where(['voterId' => $voter->id, 'remindNo' => 2])->count();
                echo "\n<br />secondReminderSent: " . $secondReminderSent;
                if ($secondReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                //continue;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $votesUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendSecondReminderToVoter($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent:" . $voter->tokenSent;
                    echo "\n<br />";

                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $voter->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);

                            $this->voterReminder->voterId = $voter->id;
                            $this->voterReminder->remindNo = 2;
                            $success = $this->voterReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->voterReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->voterReminder->errors, 'attributes' => $this->voterReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }

                    usleep(100);

                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }

                if ($counter >= 15000) {
                    break;
                }

            }
        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }
	
	public function actionSendThirdReminderToVoter()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
        // TODO: Necessary messages to Voter Reminder
        // TODO: Insert necessary records to Voter Reminder

        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->all();
        //$this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->limit(15000)->all();
        //echo "\n<br />voters: ";
        //print_r($this->voters);
        exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $voter) {

                $counter++;
                $thirdReminderSent = null;
                $this->voterReminder = new VoterReminder();

                //echo "\n<br />counter: " . $counter;
                //echo "\n<br />voter: id: " . $voter->id;
                //echo "\n<br />voter: name: " . $voter->name;
                //echo "\n<br />voter: email: " . $voter->email;

                //if (13865 != $voter->id && 13866 != $voter->id && 13867 != $voter->id && 13868 != $voter->id) { // 13865 - Yohan, 13866 - Rooban, 13867 - Steve, 13868 - Nasmy
                //    continue;
                //}

				echo "\n<br />counter: " . $counter;
				echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;

                $thirdReminderSent = $this->voterReminder->find()->where(['voterId' => $voter->id, 'remindNo' => 3])->count();
                echo "\n<br />thirdReminderSent: " . $thirdReminderSent;
                if ($thirdReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                //continue;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $votesUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendThirdReminderToVoter($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent:" . $voter->tokenSent;
                    echo "\n<br />";

                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $voter->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);

                            $this->voterReminder->voterId = $voter->id;
                            $this->voterReminder->remindNo = 3;
                            $success = $this->voterReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->voterReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->voterReminder->errors, 'attributes' => $this->voterReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }

                    usleep(100);

                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }

                if ($counter >= 15000) {
                    break;
                }

            }
        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }
	
	public function actionSendForthReminderToVoter()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
        // TODO: Necessary messages to Voter Reminder
        // TODO: Insert necessary records to Voter Reminder

        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->all();
        //$this->voters = $this->voter->find()->active()->tokenSent()->notVoted()->limit(15000)->all();
        //echo "\n<br />voters: ";
        //print_r($this->voters);
        exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $voter) {

                $counter++;
                $thirdReminderSent = null;
                $this->voterReminder = new VoterReminder();

                //echo "\n<br />counter: " . $counter;
                //echo "\n<br />voter: id: " . $voter->id;
                //echo "\n<br />voter: name: " . $voter->name;
                //echo "\n<br />voter: email: " . $voter->email;

                //if (13865 != $voter->id && 13866 != $voter->id && 13867 != $voter->id && 13868 != $voter->id) { // 13865 - Yohan, 13866 - Rooban, 13867 - Steve, 13868 - Nasmy
                //    continue;
                //}

				echo "\n<br />counter: " . $counter;
				echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;

                $forthReminderSent = $this->voterReminder->find()->where(['voterId' => $voter->id, 'remindNo' => 4])->count();
                echo "\n<br />forthReminderSent: " . $forthReminderSent;
                if ($forthReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                //continue;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $votesUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendForthReminderToVoter($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent:" . $voter->tokenSent;
                    echo "\n<br />";

                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $voter->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);

                            $this->voterReminder->voterId = $voter->id;
                            $this->voterReminder->remindNo = 4;
                            $success = $this->voterReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->voterReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->voterReminder->errors, 'attributes' => $this->voterReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }

                    usleep(100);

                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }

                if ($counter >= 15000) {
                    break;
                }

            }
        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }

    public function actionSendFinalReminderToVoter()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start sending final reminders.');

		$counter = 0;
		$success = false;
        
		$sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');
		
		$sucMsgReminder = Yii::t('app', 'Voter reminder saved successfully.');
        $errMsgReminder = Yii::t('app', 'Voter reminder save failed.');
		
        $sucMsgEmail = Yii::t('app', 'Email send successfully.');
        $errMsgEmail = Yii::t('app', 'Email send failed.');        

        $mail = new Mail();
        $voters = null;
        $tokenGenerator = new TokenGenerator();

        $voters = Voter::find()->active()->tokenSent()->notVoted()->all();
        //echo "\n<br>voters: ";
        //print_r($voters);
        //exit;
        if (!empty($voters)) {

            foreach ($voters as $voter) {
				$counter++;
                $reminderSent = 0;
				$voterReminder = null;
                				
				//echo "\n<br>counter: " . $counter;
                //echo "\n<br>id: " . $voter->id;
                //echo "\n<br>name: " . $voter->name;
				//echo "\n<br>email: " . $voter->email;
                //if (13865 != $voter->id && 13866 != $voter->id && 13867 != $voter->id && 13868 != $voter->id) {	// 13865 - Yohan, 13866 - Rooban, 13867 - Steve, 13868 - Nasmy
				/*if (5716 != $voter->id) {	// 13865 - Yohan
                    continue;
                }*/
				echo "\n<br>counter: " . $counter;
				echo "\n<br>id: " . $voter->id;
                echo "\n<br>name: " . $voter->name;
                echo "\n<br>email: " . $voter->email;
				//continue;
				//exit;
				
				$reminderSent = VoterReminder::find()->where(['voterId' => $voter->id, 'remindNo' => 4])->count();
                echo "\n<br>reminderSent: " . $reminderSent;
                if ($reminderSent > 0) {
                    continue;
                }
                //continue;
				//exit;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $tokenGenerator->generateUnique($keyLength, $voter->id);
                echo "\n<br>token: " . $voter->token;               
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br>emailToken: " . $emailToken;
				//continue;
				//exit;

				// Transaction handling: Begin
				$transaction = Yii::$app->db->beginTransaction();
				
				// Exception handling: Begin
                try {
                    $voterUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    echo "\n<br>voterUrl: " . $voterUrl;
                    //continue;
					//exit;
					
					// Save voter: Begin
					$voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
					$voter->tokenSent = 1;
					$voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
					$success = $voter->saveModel();
					echo "\n<br>voterSaveSuccess: " . $success;
					
					if ($success) {
						Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);
						
						// Save voter reminder: Begin
						$voterReminder = new VoterReminder();
						$voterReminder->voterId = $voter->id;
						$voterReminder->remindNo = 5;
						$success = $voterReminder->saveModel();
						echo "\n<br>voterReminderSaveSuccess:" . $success;
						
						if ($success) {
							Yii::$app->appLog->writeLog($sucMsgReminder, ['attributes' => $voterReminder->attributes]);
							
							// Email send: Begin
							$success = $mail->sendFinalReminderToVoter($voter->email, $voter->name, $voter->gender, $voterUrl, $voter->token);
							echo "\n<br>emailSendSuccess:" . $success;
							
							if ($success) {
								Yii::$app->appLog->writeLog($sucMsgEmail);								
							} else {
								Yii::$app->appLog->writeLog($errMsgEmail);
							}							
							// Email send: End
							
							
						} else {
							Yii::$app->appLog->writeLog($errMsgReminder, ['errors' => $voterReminder->errors, 'attributes' => $voterReminder->attributes]);
						}	
						// Save voter reminder: End
						
					} else {
						Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
					}
					// Save voter: End                    
					
                } catch (Exception $ex) {
					$success = false;
					
					if (!empty($voter->attributes) && !empty($voterReminder->attributes)) {
						$attributes['voter'] = $voter->attributes;
						$attributes['voterReminder'] = $voterReminder->attributes;
					} else if (!empty($voter->attributes) && empty($voterReminder->attributes)) {
						$attributes['voter'] = $voter->attributes;
						$attributes['voterReminder'] = null;
					} else if (empty($voter->attributes) && !empty($voterReminder->attributes)) {
						$attributes['voter'] = null;
						$attributes['voterReminder'] = $voterReminder->attributes;
					} else {
						$attributes['voter'] = null;
						$attributes['voterReminder'] = null;
					}					
					
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $attributes]);
                }	
				// Exception handling: End
				
				if ($success) {
					$transaction->commit();					
					Yii::$app->appLog->writeLog('Commit transaction.');					
				} else {
					$transaction->rollBack();
					Yii::$app->appLog->writeLog('Rollback transaction');					
				}		
				// Transaction handling: End
				echo "\n<br>";				
            }
			
        } else {
            Yii::$app->appLog->writeLog('No records to send final reminder.');
        }

        Yii::$app->appLog->writeLog('Stop sending final reminders.');
    }
	
	public function actionSendApologyVoterToken()
    {
        exit;

        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start sending apology reminders.');

        $counter = 0;
        $success = false;

        $sucMsg = Yii::t('app', 'Message saved successfully.');
        $errMsg = Yii::t('app', 'Message save failed.');

        $sucMsgReminder = Yii::t('app', 'Apology reminder saved successfully.');
        $errMsgReminder = Yii::t('app', 'Apology reminder save failed.');

        $sucMsgEmail = Yii::t('app', 'Email send successfully.');
        $errMsgEmail = Yii::t('app', 'Email send failed.');

        $mail = new Mail();
        $voterFinalReminders = null;
		
		$voterFinalReminders = VoterReminder::find()->reminderNumber(5)->all();
		exit;

        if (!empty($voterFinalReminders)) {
            foreach ($voterFinalReminders as $reminder) {
                //echo $reminder->voter->email;
                $voterId = $reminder->voterId;
                $voter = Voter::find()->where(['id' => $voterId])->one();
                $counter++;
                $reminderSent = 0;

                echo "\n<br>counter: " . $counter;
                echo "\n<br>id: " . $voter->id;
                echo "\n<br>name: " . $voter->name;
                echo "\n<br>email: " . $voter->email;
                
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br>emailToken: " . $emailToken;
                if(!empty($voter->email)) {
                    $success = $mail->sendApologyReminderToVoter($voter->email, $voter->name, $voter->gender);
                    echo "\n<br>emailSendSuccess:" . $success;
                }else {
                    echo "\n<br>emailNotFound:";
                }

                if ($success) {
                    Yii::$app->appLog->writeLog($sucMsgEmail);
                } else {
                    Yii::$app->appLog->writeLog($errMsgEmail);
                }
            }
        } else {
            Yii::$app->appLog->writeLog('No records to send apology reminder.');
        }

        Yii::$app->appLog->writeLog('Stop sending apology reminders.');
    }


      public function actionPreseeNotifyEmail()
    {
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start sending final reminders.');

        $counter = 0;
        $success = false;

        $sucMsg = Yii::t('app', 'Voter saved successfully.');
        $errMsg = Yii::t('app', 'Voter save failed.');

        $sucMsgReminder = Yii::t('app', 'Voter reminder saved successfully.');
        $errMsgReminder = Yii::t('app', 'Voter reminder save failed.');

        $sucMsgEmail = Yii::t('app', 'Email send successfully.');
        $errMsgEmail = Yii::t('app', 'Email send failed.');

        $mail = new Mail();
        $voters = null;
        $tokenGenerator = new TokenGenerator();

        $voters = Voter::find()->active()->tokenSent()->all();
        //echo "\n<br>voters: ";
        //print_r($voters);
        //exit;
        if (!empty($voters)) {

            foreach ($voters as $voter) {
                $counter++;
                $reminderSent = 0;
                $voterReminder = null;

                //echo "\n<br>counter: " . $counter;
                //echo "\n<br>id: " . $voter->id;
                //echo "\n<br>name: " . $voter->name;
                //echo "\n<br>email: " . $voter->email;
                //if (13865 != $voter->id && 13866 != $voter->id && 13867 != $voter->id && 13868 != $voter->id) {   // 13865 - Yohan, 13866 - Rooban, 13867 - Steve, 13868 - Nasmy
                /*if (5716 != $voter->id) { // 13865 - Yohan
                    continue;
                }*/

                echo "\n<br>counter: " . $counter;
                echo "\n<br>id: " . $voter->id;
                echo "\n<br>name: " . $voter->name;
                echo "\n<br>email: " . $voter->email;
                //continue;
                //exit;
                //$voter->email = "nasmy@keeneye.solutions";

                $reminderSent = VoterReminder::find()->where(['voterId' => $voter->id, 'remindNo' => 7])->count();
                echo "\n<br>reminderSent: " . $reminderSent;
                if ($reminderSent > 0) {
                    continue;
                }
                //continue;
                //exit;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $tokenGenerator->generateUnique($keyLength, $voter->id);
                echo "\n<br>token: " . $voter->token;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br>emailToken: " . $emailToken;
                //continue;
                //exit;

                // Transaction handling: Begin
                $transaction = Yii::$app->db->beginTransaction();

                // Exception handling: Begin
                try {
                    $voterUrl = Yii::$app->params['votesUrl'] . $emailToken;
                    //echo "\n<br>voterUrl: " . $voterUrl;
                    //continue;
                    //exit;

                    // Save voter: Begin
                    $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                    $voter->tokenSent = 1;
                    $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                    $success = $voter->saveModel();
                    echo "\n<br>voterSaveSuccess: " . $success;

                    if ($success) {
                        Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);

                        // Save voter reminder: Begin
                        $voterReminder = new VoterReminder();
                        $voterReminder->voterId = $voter->id;
                        $voterReminder->remindNo = 7;
                        $success = $voterReminder->saveModel();
                        echo "\n<br>voterReminderSaveSuccess:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsgReminder, ['attributes' => $voterReminder->attributes]);

                            // Email send: Begin
                            $success = $mail->sendPreseeWinnerEmail($voter->email);
                            echo "\n<br>emailSendSuccess:" . $success;

                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsgEmail);
                            } else {
                                Yii::$app->appLog->writeLog($errMsgEmail);
                            }
                            // Email send: End


                        } else {
                            Yii::$app->appLog->writeLog($errMsgReminder, ['errors' => $voterReminder->errors, 'attributes' => $voterReminder->attributes]);
                        }
                        // Save voter reminder: End

                    } else {
                        Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                    }
                    // Save voter: End

                } catch (Exception $ex) {
                    $success = false;

                    if (!empty($voter->attributes) && !empty($voterReminder->attributes)) {
                        $attributes['voter'] = $voter->attributes;
                        $attributes['voterReminder'] = $voterReminder->attributes;
                    } else if (!empty($voter->attributes) && empty($voterReminder->attributes)) {
                        $attributes['voter'] = $voter->attributes;
                        $attributes['voterReminder'] = null;
                    } else if (empty($voter->attributes) && !empty($voterReminder->attributes)) {
                        $attributes['voter'] = null;
                        $attributes['voterReminder'] = $voterReminder->attributes;
                    } else {
                        $attributes['voter'] = null;
                        $attributes['voterReminder'] = null;
                    }

                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $attributes]);
                }
                // Exception handling: End

                if ($success) {
                    $transaction->commit();
                    Yii::$app->appLog->writeLog('Commit transaction.');
                } else {
                    $transaction->rollBack();
                    Yii::$app->appLog->writeLog('Rollback transaction');
                }
                // Transaction handling: End
                echo "\n<br>";
            }

        } else {
            Yii::$app->appLog->writeLog('No records to send final reminder.');
        }

        Yii::$app->appLog->writeLog('Stop sending final reminders.');

    }

    public function actionSendCustomVoterToken()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Voter saved successfully');
        $errMsg = Yii::t('app', 'Voter save failed');
        $emailSucMsg = Yii::t('app', 'Email sent successfully');
        $emailErrMsg = Yii::t('app', 'Email send failed');

        $this->mail = new Mail();
        $this->voter = new Voter();
        $this->voters = null;
        $this->tokenGenerator = new TokenGenerator();

        // Custom voters array
        $voters = array(
            array('id' => '5406', 'email' => 'adtraviata@gmail.com'),
            array('id' => '1606', 'email' => 'annesophie.balbir@gmail.com'),
            array('id' => '2228', 'email' => 'fredbezies@gmail.com'),
            array('id' => '2101', 'email' => 'ybernard@lefigaro.fr'),
            array('id' => '2294', 'email' => 'g.biolley@charentelibre.fr'),
            array('id' => '2022', 'email' => 'motsdemode@gmail.com'),
            array('id' => '4052', 'email' => 'mcornic@prismamedia.com'),
            array('id' => '2288', 'email' => 'w.billiard@lest-eclair.fr'),
            array('id' => '2713', 'email' => 'ressources@crjbfc.org'),
            array('id' => '1881', 'email' => 'julia.beaumet@leprogres.fr'),
            array('id' => '2389', 'email' => 'sonia.blondetrodriguez@gmail.com'),
            array('id' => '3776', 'email' => 'presse@fastandfood.fr'),
            array('id' => '2676', 'email' => 'thierry.boulant@radiofrance.com'),
            array('id' => '1284', 'email' => 'eangioletti@angiocom.fr'),
            array('id' => '2066', 'email' => 'emeric-radio@hotmail.fr'),
            array('id' => '2188', 'email' => 'bloglivres.emilie@gmail.com'),
            array('id' => '2185', 'email' => 'stephanebesnier@tvrennes35bretagne.fr'),
            array('id' => '2577', 'email' => 'dominiquebosch@grandpalais.com'),
            array('id' => '1237', 'email' => 'cp@actualitte.com'),
            array('id' => '2527', 'email' => 'isabono@gmail.com'),
            array('id' => '4512', 'email' => 'alexandre.decarvalho@live.fr'),
            array('id' => '4302', 'email' => 'lo@86andco.fr'),
            array('id' => '4970', 'email' => 'vinyadacilwen69@gmail.com'),
            array('id' => '2334', 'email' => 'dblanchard@racines85.fr'),
            array('id' => '4073', 'email' => 'isabelle.costa@bayard-presse.com'),
            array('id' => '5207', 'email' => 'yvette.doria@wanadoo.fr'),
            array('id' => '3134', 'email' => 'patrice.caillet@radiofrance.com'),
            array('id' => '2124', 'email' => 'stephane.bern@rtl.fr'),
            array('id' => '1414', 'email' => 'ltpaterson588@gmail.com'),
            array('id' => '3661', 'email' => 'jmch@noos.fr'),
            array('id' => '5364', 'email' => 'solcita@rocketmail.com'),
            array('id' => '4838', 'email' => 'edemont@roannais-agglomeration.fr'),
            array('id' => '1994', 'email' => 'benarochecamille@gmail.com'),
            array('id' => '4034', 'email' => 'anne.cordonfabregue@gmail.com'),
            array('id' => '5189', 'email' => 'elisabethdonetti@hotmail.com'),
            array('id' => '4781', 'email' => 'jetsociety@jet-society.com'),
            array('id' => '4827', 'email' => 'mdemmanuele@etbaam.com'),
            array('id' => '3575', 'email' => 'charre.jerome@gmail.com'),
            array('id' => '1364', 'email' => 'amo@forum-thalie.fr'),
            array('id' => '5171', 'email' => 'vdokan@gmail.com'),
            array('id' => '3817', 'email' => 'jean-pierre.clatot@afp.com'),
            array('id' => '3246', 'email' => 'etiennecarbonnier@bangumi.fr'),
            array('id' => '4213', 'email' => 'lcroiset@challenges.fr'),
            array('id' => '3567', 'email' => 'lcharpentier@tf1.fr'),
            array('id' => '2981', 'email' => 'ebrunet@nouvelobs.com'),
            array('id' => '4727', 'email' => 'ad@infosnews.fr'),
            array('id' => '4482', 'email' => 'adrien@groupedeblanzy.com'),
            array('id' => '3123', 'email' => 'marie.cailleaud@tulleagglo.fr'),
            array('id' => '2592', 'email' => 'jean-baptiste.botella@centrefrance.com'),
            array('id' => '3326', 'email' => 'stephane@heteroclite.org'),
            array('id' => '1061', 'email' => 'journet.adeline@gmail.com'),
            array('id' => '4780', 'email' => 'ddelseny@leparisien.fr'),
            array('id' => '4728', 'email' => 'thierry.delettre@radiofrance.com'),
            array('id' => '4694', 'email' => 'pdelavalette@lepoint.fr'),
            array('id' => '3905', 'email' => 'nellucohn@gmail.com'),
            array('id' => '4854', 'email' => 'florence@herault-tribune.com'),
            array('id' => '3653', 'email' => 'coline.chavaroche@gmail.com'),
            array('id' => '2974', 'email' => 'mbrunel@m6.fr'),
            array('id' => '1458', 'email' => 'jean-baptiste.audibert@radiofrance.com'),
            array('id' => '2991', 'email' => 'frederique.brun@radiofrance.com'),
            array('id' => '3080', 'email' => 'arnaudbyhet@hotmail.com'),
            array('id' => '3242', 'email' => 'anne-marie.carbonari@ville-moirans.fr'),
            array('id' => '3863', 'email' => 'gcloup@prismamedia.com'),
            array('id' => '2957', 'email' => 'erwanbruckert@gmail.com'),
            array('id' => '2445', 'email' => 'f.boitelle@presse-normande.com'),
            array('id' => '1587', 'email' => 'baillargeon@gmail.com'),
            array('id' => '2293', 'email' => 'fabien.binacchi@gmail.com'),
            array('id' => '3796', 'email' => 'cciminelli@nextinteractive.fr'),
            array('id' => '2111', 'email' => 'paul-alexis.bernard@centrefrance.com'),
            array('id' => '4149', 'email' => 'pcourty@tribumove.com'),
            array('id' => '1318', 'email' => 'fa@bodoi.com'),
            array('id' => '2548', 'email' => 'pteapotes@free.fr'),
            array('id' => '3597', 'email' => 'h.chassain@sudouest.fr'),
            array('id' => '2860', 'email' => 'jacqueline.brenner@ville-chartres.fr'),
            array('id' => '2079', 'email' => 'f.berg@charentelibre.fr'),
            array('id' => '3732', 'email' => 'rchiche@hotmail.com'),
            array('id' => '1218', 'email' => 'l.ameline@lapressedelamanche.fr'),
            array('id' => '2909', 'email' => 'd.brimont@presse-normande.com'),
            array('id' => '2324', 'email' => 'julien.blaine@free.fr'),
            array('id' => '3726', 'email' => 'tcheze@studiocinelive.com'),
            array('id' => '2999', 'email' => 'gilles@observatoiredesmedias.com'),
            array('id' => '3372', 'email' => 'catherine.catala@ville-cergy.fr'),
            array('id' => '2500', 'email' => 'ma.bonnefoy@charentelibre.fr'),
            array('id' => '3025', 'email' => 'mariemandine@live.fr'),
            array('id' => '1934', 'email' => 'sudouest.mugron@hotmail.com'),
            array('id' => '2270', 'email' => 'marc.kamikaze@gmail.com'),
            array('id' => '1805', 'email' => 'hohxb@hotmail.com'),
            array('id' => '2714', 'email' => 'theodorebourdeau@bangumi.fr'),
            array('id' => '2353', 'email' => 'maeva@projetcoal.fr'),
            array('id' => '2052', 'email' => 'paristonkar@online.fr'),
            array('id' => '1831', 'email' => 'steeve.baumann@canal-plus.com'),
            array('id' => '2359', 'email' => 'edjablanvillain@gmail.com'),
            array('id' => '2115', 'email' => 'jbernatas@leparisien.fr'),
            array('id' => '2121', 'email' => 'jnb@grand-ecart.fr'),
            array('id' => '1995', 'email' => 'Leslie.benaroch@lagardere-active.com'),
            array('id' => '1959', 'email' => 'nbellet@premiere.fr'),
            array('id' => '1314', 'email' => 'laurine.clementine@gmail.com'),
            array('id' => '1242', 'email' => 'anahaddict.blog@gmail.com'),
            array('id' => '13844', 'email' => 'yohan@keeneye.solutions'),
            array('id' => '13852', 'email' => 'steve@keeneye.solutions'),
            array('id' => '13856', 'email' => 'disath@keeneye.solutions'),
        );

        $this->voters = $voters;
        //echo "\n<br />voters: ";
        //print_r($this->voters);
        exit;
        if (!empty($this->voters)) {

            foreach ($this->voters as $arr) {
                //echo "\n<br />voter: id: " . $arr['id'];
                //echo "\n<br />voter: email: " . $arr['email'];
                //continue;
                $voter = Voter::findOne($arr['id']);
                //if (13844 != $voter->id && 13852 != $voter->id && 13856 != $voter->id) { // 13844 - Yohan, 13852 - Steve, 13856 - Disath
                if (13844 != $voter->id) { // 13844 - Yohan
                    continue;
                }
                echo "\n<br />voter: id: " . $voter->id;
                echo "\n<br />voter: name: " . $voter->name;
                echo "\n<br />voter: email: " . $voter->email;
                continue;

                $idLength = strlen($voter->id);
                $keyLength = Yii::$app->params['voterTokenLength'] - $idLength;
                $voter->token = $this->tokenGenerator->generateUnique($keyLength, $voter->id);
                //echo "\n<br />voter: token: " . $voter->token;
                $emailToken = urlencode(base64_encode($voter->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                continue;
                exit;

				try {
                    $step = 8;
                    $votesUrl = urlencode(Yii::$app->params['votesUrl'] . $emailToken . '&step=8');
                    echo "\n<br />votesUrl: " . $votesUrl;
                    //continue;
                    $voter->tokenSent = $this->mail->sendCustomVoterTokenEmail($voter->email, $voter->name, $voter->gender, $votesUrl, $voter->token);
                    echo "\n<br />tokenSent:" . $voter->tokenSent;
                    echo "\n<br />";
                    sleep(1);

                    if ($voter->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $voter->attributes]);

                        $voter->token = password_hash($voter->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $voter->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $voter->voted = 0;
                        //$success = false;
                        $success = $voter->saveModel();
                        //echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $voter->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $voter->errors, 'attributes' => $voter->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $voter->attributes]);
                    }
                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
                }
            }
        } else {
            Yii::$app->appLog->writeLog('No records to send token.');
        }

        Yii::$app->appLog->writeLog('Stop.');
    }

}
