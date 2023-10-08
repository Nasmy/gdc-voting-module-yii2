<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\Mail;
use app\components\TokenGenerator;
use app\models\Producer;
use app\models\ProducerReminder;

/*
 * This controller send notifications to intended users.
 * Command runs on different time slots.
 */

class NotifyproducerController extends Controller
{
    public $notificationQueue;
    public $notification;
    public $mail;
    public $producer;
    public $producers;
    public $producerReminder;
    public $tokenGenerator;

    public function actionPressRelease()
    {
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Press Send successfully');
        $errMsg = Yii::t('app', 'Press Send failed');
        $emailSucMsg = Yii::t('app', 'Email with token sent successfully');
        $emailErrMsg = Yii::t('app', 'Email with token send failed');

        $counter = 0;
        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();
        $this->producers = $this->producer->find()->active()->limitQuery(6000, 6700)->all();
        echo "\n<br />producers: ";
        echo "\n<br />producers - count: " . count($this->producers);
        if (!empty($this->producers)) {
            foreach ($this->producers as $producer) {
                $counter++;

                echo "\n<br />counter: " . $counter;
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;
                //sleep(1);
                //continue;
                // exit;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);
                //continue;
                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;

                    $producer->tokenSent = $this->mail->sendProducerPrese($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";


                    if ($producer->tokenSent) {
                        $producer->tokenSent = 0;
						$producer->status = 0;
                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        //$success = false;
                        $success = $producer->saveModel();
//                        echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }

                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
                    }

                    sleep(1);


                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $ex->getMessage()]);
                }
            }
        }
    }

    public function actionPressReleaseTest()
    {
        // echo "nasmy";
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Press Send successfully');
        $errMsg = Yii::t('app', 'Press Send failed');
        $emailSucMsg = Yii::t('app', 'Email with token sent successfully');
        $emailErrMsg = Yii::t('app', 'Email with token send failed');

        $counter = 0;
        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->notTokenSent()->all();

        echo "\n<br />producers: ";

        echo "\n<br />producers - count: " . count($this->producers);
        // exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;

                if ($counter < 1000) {
                    continue;
                }

                echo "\n<br />counter: " . $counter;
                echo "\n<br />producer: id: " . $producer->id;


                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;
                //sleep(1);
                //continue;
                // exit;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;

                    $producer->tokenSent = $this->mail->sendProducerPrese($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";


                    if ($producer->tokenSent) {
  
                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        //$success = false;
                        $success = $producer->saveModel();
//                        echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }

                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
                    }

                    sleep(1);


                } catch (Exception $ex) {
                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $ex->getMessage()]);
                }
            }
        }
    }

    public function actionSendPoducerToken()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully');
        $errMsg = Yii::t('app', 'Producer save failed');
        $emailSucMsg = Yii::t('app', 'Email with token sent successfully');
        $emailErrMsg = Yii::t('app', 'Email with token send failed');

        $counter = 0;
        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();
        $this->producers = $this->producer->find()->active()->notVoted()->notTokenSent()->all();
        // exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;

                if ($counter > 1000) {
                    continue;
                }
                echo "\n<br />counter: " . $counter;


                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;
                //sleep(1);
                //continue;
                // exit;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);
                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    print_r($producer);die;
                    $producer->tokenSent = $this->mail->sendProducerTokenEmail($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        //$success = false;
                        $success = $producer->saveModel();

                        //echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }

                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendFirstReminderToProducer()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully.');
        $errMsg = Yii::t('app', 'Producer save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');


        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->tokenSent()->notRegistered()->all();

        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;

                if ($counter > 6500) {
                    continue;
                }

                $firstReminderSent = null;
                $this->producerReminder = new ProducerReminder();

                echo "\n<br />counter: " . $counter;

                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;
                $firstReminderSent = $this->producerReminder->find()->where(['producerId' => $producer->id, 'remindNo' => 5])->count();
                echo "\n<br />finalReminderSent: " . $firstReminderSent;
                if ($firstReminderSent > 0) {
                    continue;
                }
                //continue;
                //exit;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendFinalReminderToProducer($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent: " . $producer->tokenSent;

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $producer->saveModel();
                        echo "\n<br />success: " . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);

                            $this->producerReminder->producerId = $producer->id;
                            $this->producerReminder->remindNo = 5;
                            $success = $this->producerReminder->saveModel();
                            echo "\n<br />success: " . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->producerReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->producerReminder->errors, 'attributes' => $this->producerReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendSecondReminderToProducer()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully.');
        $errMsg = Yii::t('app', 'Producer save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
        // TODO: Necessary messages to Producer Reminder
        // TODO: Insert necessary records to Producer Reminder

        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->tokenSent()->notRegistered()->all();

        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;
                $secondReminderSent = null;
                $this->producerReminder = new ProducerReminder();

                echo "\n<br />counter: " . $counter;
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;


                $secondReminderSent = $this->producerReminder->find()->where(['producerId' => $producer->id, 'remindNo' => 2])->count();
                echo "\n<br />secondReminderSent: " . $secondReminderSent;
                if ($secondReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendSecondReminderToProducer($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $producer->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);

                            $this->producerReminder->producerId = $producer->id;
                            $this->producerReminder->remindNo = 2;
                            $success = $this->producerReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->producerReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->producerReminder->errors, 'attributes' => $this->producerReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendThirdReminderToProducer()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully.');
        $errMsg = Yii::t('app', 'Producer save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');


        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->tokenSent()->notRegistered()->all();

        exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;
                $thirdReminderSent = null;
                $this->producerReminder = new ProducerReminder();

                echo "\n<br />counter: " . $counter;
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;

                $thirdReminderSent = $this->producerReminder->find()->where(['producerId' => $producer->id, 'remindNo' => 3])->count();
                echo "\n<br />thirdReminderSent: " . $thirdReminderSent;
                if ($thirdReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendThirdReminderToProducer($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $producer->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);

                            $this->producerReminder->producerId = $producer->id;
                            $this->producerReminder->remindNo = 3;
                            $success = $this->producerReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->producerReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->producerReminder->errors, 'attributes' => $this->producerReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendForthReminderToProducer()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $counter = 0;
        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully.');
        $errMsg = Yii::t('app', 'Producer save failed.');
        $emailSucMsg = Yii::t('app', 'Email sent successfully.');
        $emailErrMsg = Yii::t('app', 'Email send failed.');
 

        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->tokenSent()->notRegistered()->all();

        exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;
                $thirdReminderSent = null;
                $this->producerReminder = new ProducerReminder();


                echo "\n<br />counter: " . $counter;
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;

                $forthReminderSent = $this->producerReminder->find()->where(['producerId' => $producer->id, 'remindNo' => 4])->count();
                echo "\n<br />forthReminderSent: " . $forthReminderSent;
                if ($forthReminderSent > 0) {
                    continue;
                }
                //continue;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendForthReminderToProducer($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $success = $producer->saveModel();
                        echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);

                            $this->producerReminder->producerId = $producer->id;
                            $this->producerReminder->remindNo = 4;
                            $success = $this->producerReminder->saveModel();
                            echo "\n<br />success:" . $success;
                            echo "\n<br />";
                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $this->producerReminder->attributes]);
                            } else {
                                Yii::$app->appLog->writeLog($errMsg, ['errors' => $this->producerReminder->errors, 'attributes' => $this->producerReminder->attributes]);
                            }

                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendFinalReminderToProducer()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start sending final reminders.');

        $counter = 0;
        $success = false;

        $sucMsg = Yii::t('app', 'Producer saved successfully.');
        $errMsg = Yii::t('app', 'Producer save failed.');

        $sucMsgReminder = Yii::t('app', 'Producer reminder saved successfully.');
        $errMsgReminder = Yii::t('app', 'Producer reminder save failed.');

        $sucMsgEmail = Yii::t('app', 'Email send successfully.');
        $errMsgEmail = Yii::t('app', 'Email send failed.');

        $mail = new Mail();
        $producers = null;
        $tokenGenerator = new TokenGenerator();

        $producers = Producer::find()->active()->tokenSent()->notRegistered()->all();
 
        if (!empty($producers)) {

            foreach ($producers as $producer) {
                $counter++;
                $reminderSent = 0;
                $producerReminder = null;

                echo "\n<br>counter: " . $counter;

                if (1186 != $producer->id) { 
                    continue;
                }
                echo "\n<br>counter: " . $counter;
                echo "\n<br>id: " . $producer->id;
                echo "\n<br>name: " . $producer->name;
                echo "\n<br>email: " . $producer->email;
                //continue;
                //exit;

                $reminderSent = ProducerReminder::find()->where(['producerId' => $producer->id, 'remindNo' => 4])->count();
                echo "\n<br>reminderSent: " . $reminderSent;
                if ($reminderSent > 0) {
                    continue;
                }
                //continue;
                //exit;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $tokenGenerator->generateUnique($keyLength, $producer->id);
                echo "\n<br>token: " . $producer->token;
                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br>emailToken: " . $emailToken;
                //continue;
                //exit;

                // Transaction handling: Begin
                $transaction = Yii::$app->db->beginTransaction();

                // Exception handling: Begin
                try {
                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br>registerUrl: " . $registerUrl;
                    //continue;
                    //exit;


                    $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                    $producer->tokenSent = 5;
                    $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                    $success = $producer->saveModel();
                    echo "\n<br>producerSaveSuccess: " . $success;

                    if ($success) {
                        Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);

                        // Save producer reminder: Begin
                        $producerReminder = new ProducerReminder();
                        $producerReminder->producerId = $producer->id;
                        $producerReminder->remindNo = 5;
                        $success = $producerReminder->saveModel();
                        echo "\n<br>producerReminderSaveSuccess:" . $success;

                        if ($success) {
                         Yii::$app->appLog->writeLog($sucMsgReminder, ['attributes' => $producerReminder->attributes]);

                            // Email send: Begin
                            $success = $mail->sendFinalReminderToProducer($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                            echo "\n<br>emailSendSuccess:" . $success;

                            if ($success) {
                                Yii::$app->appLog->writeLog($sucMsgEmail);
                            } else {
                                Yii::$app->appLog->writeLog($errMsgEmail);
                            }
                            // Email send: End


                        } else {
                            Yii::$app->appLog->writeLog($errMsgReminder, ['errors' => $producerReminder->errors, 'attributes' => $producerReminder->attributes]);
                        }
                        // Save producer reminder: End

                    } else {
                        Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                    }
                    // Save producer: End

                } catch (Exception $ex) {
                    $success = false;

                    if (!empty($producer->attributes) && !empty($producerReminder->attributes)) {
                        $attributes['producer'] = $producer->attributes;
                        $attributes['producerReminder'] = $producerReminder->attributes;
                    } else if (!empty($producer->attributes) && empty($producerReminder->attributes)) {
                        $attributes['producer'] = $producer->attributes;
                        $attributes['producerReminder'] = null;
                    } else if (empty($producer->attributes) && !empty($producerReminder->attributes)) {
                        $attributes['producer'] = null;
                        $attributes['producerReminder'] = $producerReminder->attributes;
                    } else {
                        $attributes['producer'] = null;
                        $attributes['producerReminder'] = null;
                    }

                    Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $attributes]);
                }
                // Exception handling: End
            }

        } else {
            Yii::$app->appLog->writeLog('No records to send final reminder.');
        }

        Yii::$app->appLog->writeLog('Stop sending final reminders.');
    }

    public function actionSendCustomProducerToken()
    {
        exit;
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully');
        $errMsg = Yii::t('app', 'Producer save failed');
        $emailSucMsg = Yii::t('app', 'Email sent successfully');
        $emailErrMsg = Yii::t('app', 'Email send failed');

        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        // Custom producers array
        $producers = array(
            // array('id' => '5406', 'email' => 'adtraviata@gmail.com'),
            // array('id' => '1606', 'email' => 'annesophie.balbir@gmail.com'),
            // array('id' => '2228', 'email' => 'fredbezies@gmail.com'),
            // array('id' => '2101', 'email' => 'ybernard@lefigaro.fr'),
            // array('id' => '2294', 'email' => 'g.biolley@charentelibre.fr'),
            // array('id' => '2022', 'email' => 'motsdemode@gmail.com'),
            // array('id' => '4052', 'email' => 'mcornic@prismamedia.com'),
            // array('id' => '2288', 'email' => 'w.billiard@lest-eclair.fr'),
            // array('id' => '2713', 'email' => 'ressources@crjbfc.org'),
            // array('id' => '1881', 'email' => 'julia.beaumet@leprogres.fr'),
            // array('id' => '2389', 'email' => 'sonia.blondetrodriguez@gmail.com'),
            // array('id' => '3776', 'email' => 'presse@fastandfood.fr'),
            // array('id' => '2676', 'email' => 'thierry.boulant@radiofrance.com'),
            // array('id' => '1284', 'email' => 'eangioletti@angiocom.fr'),
            // array('id' => '2066', 'email' => 'emeric-radio@hotmail.fr'),
            // array('id' => '2188', 'email' => 'bloglivres.emilie@gmail.com'),
            // array('id' => '2185', 'email' => 'stephanebesnier@tvrennes35bretagne.fr'),
            // array('id' => '2577', 'email' => 'dominiquebosch@grandpalais.com'),
            // array('id' => '1237', 'email' => 'cp@actualitte.com'),
            // array('id' => '2527', 'email' => 'isabono@gmail.com'),
            // array('id' => '4512', 'email' => 'alexandre.decarvalho@live.fr'),
            // array('id' => '4302', 'email' => 'lo@86andco.fr'),
            // array('id' => '4970', 'email' => 'vinyadacilwen69@gmail.com'),
            // array('id' => '2334', 'email' => 'dblanchard@racines85.fr'),
            // array('id' => '4073', 'email' => 'isabelle.costa@bayard-presse.com'),
            // array('id' => '5207', 'email' => 'yvette.doria@wanadoo.fr'),
            // array('id' => '3134', 'email' => 'patrice.caillet@radiofrance.com'),
            // array('id' => '2124', 'email' => 'stephane.bern@rtl.fr'),
            // array('id' => '1414', 'email' => 'ltpaterson588@gmail.com'),
            // array('id' => '3661', 'email' => 'jmch@noos.fr'),
            // array('id' => '5364', 'email' => 'solcita@rocketmail.com'),
            // array('id' => '4838', 'email' => 'edemont@roannais-agglomeration.fr'),
            // array('id' => '1994', 'email' => 'benarochecamille@gmail.com'),
            // array('id' => '4034', 'email' => 'anne.cordonfabregue@gmail.com'),
            // array('id' => '5189', 'email' => 'elisabethdonetti@hotmail.com'),
            // array('id' => '4781', 'email' => 'jetsociety@jet-society.com'),
            // array('id' => '4827', 'email' => 'mdemmanuele@etbaam.com'),
            // array('id' => '3575', 'email' => 'charre.jerome@gmail.com'),
            // array('id' => '1364', 'email' => 'amo@forum-thalie.fr'),
            // array('id' => '5171', 'email' => 'vdokan@gmail.com'),
            // array('id' => '3817', 'email' => 'jean-pierre.clatot@afp.com'),
            // array('id' => '3246', 'email' => 'etiennecarbonnier@bangumi.fr'),
            // array('id' => '4213', 'email' => 'lcroiset@challenges.fr'),
            // array('id' => '3567', 'email' => 'lcharpentier@tf1.fr'),
            // array('id' => '2981', 'email' => 'ebrunet@nouvelobs.com'),
            // array('id' => '4727', 'email' => 'ad@infosnews.fr'),
            // array('id' => '4482', 'email' => 'adrien@groupedeblanzy.com'),
            // array('id' => '3123', 'email' => 'marie.cailleaud@tulleagglo.fr'),
            // array('id' => '2592', 'email' => 'jean-baptiste.botella@centrefrance.com'),
            // array('id' => '3326', 'email' => 'stephane@heteroclite.org'),
            // array('id' => '1061', 'email' => 'journet.adeline@gmail.com'),
            // array('id' => '4780', 'email' => 'ddelseny@leparisien.fr'),
            // array('id' => '4728', 'email' => 'thierry.delettre@radiofrance.com'),
            // array('id' => '4694', 'email' => 'pdelavalette@lepoint.fr'),
            // array('id' => '3905', 'email' => 'nellucohn@gmail.com'),
            // array('id' => '4854', 'email' => 'florence@herault-tribune.com'),
            // array('id' => '3653', 'email' => 'coline.chavaroche@gmail.com'),
            // array('id' => '2974', 'email' => 'mbrunel@m6.fr'),
            // array('id' => '1458', 'email' => 'jean-baptiste.audibert@radiofrance.com'),
            // array('id' => '2991', 'email' => 'frederique.brun@radiofrance.com'),
            // array('id' => '3080', 'email' => 'arnaudbyhet@hotmail.com'),
            // array('id' => '3242', 'email' => 'anne-marie.carbonari@ville-moirans.fr'),
            // array('id' => '3863', 'email' => 'gcloup@prismamedia.com'),
            // array('id' => '2957', 'email' => 'erwanbruckert@gmail.com'),
            // array('id' => '2445', 'email' => 'f.boitelle@presse-normande.com'),
            // array('id' => '1587', 'email' => 'baillargeon@gmail.com'),
            // array('id' => '2293', 'email' => 'fabien.binacchi@gmail.com'),
            // array('id' => '3796', 'email' => 'cciminelli@nextinteractive.fr'),
            // array('id' => '2111', 'email' => 'paul-alexis.bernard@centrefrance.com'),
            // array('id' => '4149', 'email' => 'pcourty@tribumove.com'),
            // array('id' => '1318', 'email' => 'fa@bodoi.com'),
            // array('id' => '2548', 'email' => 'pteapotes@free.fr'),
            // array('id' => '3597', 'email' => 'h.chassain@sudouest.fr'),
            // array('id' => '2860', 'email' => 'jacqueline.brenner@ville-chartres.fr'),
            // array('id' => '2079', 'email' => 'f.berg@charentelibre.fr'),
            // array('id' => '3732', 'email' => 'rchiche@hotmail.com'),
            // array('id' => '1218', 'email' => 'l.ameline@lapressedelamanche.fr'),
            // array('id' => '2909', 'email' => 'd.brimont@presse-normande.com'),
            // array('id' => '2324', 'email' => 'julien.blaine@free.fr'),
            // array('id' => '3726', 'email' => 'tcheze@studiocinelive.com'),
            // array('id' => '2999', 'email' => 'gilles@observatoiredesmedias.com'),
            // array('id' => '3372', 'email' => 'catherine.catala@ville-cergy.fr'),
            // array('id' => '2500', 'email' => 'ma.bonnefoy@charentelibre.fr'),
            // array('id' => '3025', 'email' => 'mariemandine@live.fr'),
            // array('id' => '1934', 'email' => 'sudouest.mugron@hotmail.com'),
            // array('id' => '2270', 'email' => 'marc.kamikaze@gmail.com'),
            // array('id' => '1805', 'email' => 'hohxb@hotmail.com'),
            // array('id' => '2714', 'email' => 'theodorebourdeau@bangumi.fr'),
            // array('id' => '2353', 'email' => 'maeva@projetcoal.fr'),
            // array('id' => '2052', 'email' => 'paristonkar@online.fr'),
            // array('id' => '1831', 'email' => 'steeve.baumann@canal-plus.com'),
            // array('id' => '2359', 'email' => 'edjablanvillain@gmail.com'),
            // array('id' => '2115', 'email' => 'jbernatas@leparisien.fr'),
            // array('id' => '2121', 'email' => 'jnb@grand-ecart.fr'),
            // array('id' => '1995', 'email' => 'Leslie.benaroch@lagardere-active.com'),
            // array('id' => '1959', 'email' => 'nbellet@premiere.fr'),
            // array('id' => '1314', 'email' => 'laurine.clementine@gmail.com'),
            // array('id' => '1242', 'email' => 'anahaddict.blog@gmail.com'),
            // array('id' => '13844', 'email' => 'yohan@keeneye.solutions'),
            // array('id' => '13852', 'email' => 'steve@keeneye.solutions'),
            // array('id' => '13856', 'email' => 'disath@keeneye.solutions'),
        );

        $this->producers = $producers;

        exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $arr) {

                $producer = Producer::findOne($arr['id']);

                if (13844 != $producer->id) { 
                    continue;
                }
                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;
                continue;

                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);
                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                continue;
                exit;

                try {
                    $step = 8;
                    $registerUrl = urlencode(Yii::$app->params['registerUrl'] . $emailToken . '&step=8');
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendCustomProducerTokenEmail($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";
                    sleep(1);

                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        $producer->registered = 0;
                        //$success = false;
                        $success = $producer->saveModel();
                        //echo "\n<br />success:" . $success;

                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }
                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
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

    public function actionSendRemiderTest() 
    {
    
        Yii::$app->appLog->action = __CLASS__;
        Yii::$app->appLog->uniqid = uniqid();
        Yii::$app->appLog->logType = 3;

        Yii::$app->appLog->writeLog('Start');

        $success = false;
        $sucMsg = Yii::t('app', 'Producer saved successfully');
        $errMsg = Yii::t('app', 'Producer save failed');
        $emailSucMsg = Yii::t('app', 'Email with token sent successfully');
        $emailErrMsg = Yii::t('app', 'Email with token send failed');

        $counter = 0;
        $this->mail = new Mail();
        $this->producer = new Producer();
        $this->producers = null;
        $this->tokenGenerator = new TokenGenerator();

        $this->producers = $this->producer->find()->active()->notRegistered()->notTokenSent()->all();


        // exit;
        if (!empty($this->producers)) {

            foreach ($this->producers as $producer) {

                $counter++;


                echo "\n<br />counter: " . $counter;


                echo "\n<br />producer: id: " . $producer->id;
                echo "\n<br />producer: name: " . $producer->name;
                echo "\n<br />producer: email: " . $producer->email;


                $idLength = strlen($producer->id);
                $keyLength = Yii::$app->params['producerTokenLength'] - $idLength;
                $producer->token = $this->tokenGenerator->generateUnique($keyLength, $producer->id);

                $emailToken = urlencode(base64_encode($producer->token . Yii::$app->params['staticSalt']));
                echo "\n<br />emailToken: " . $emailToken;
                //continue;
                //exit;

                try {

                    $registerUrl = Yii::$app->params['registerUrl'] . $emailToken;
                    echo "\n<br />registerUrl: " . $registerUrl;
                    //continue;
                    $producer->tokenSent = $this->mail->sendProducerTokenEmail($producer->email, $producer->name, $producer->gender, $registerUrl, $producer->token);
                    echo "\n<br />tokenSent:" . $producer->tokenSent;
                    echo "\n<br />";


                    if ($producer->tokenSent) {

                        Yii::$app->appLog->writeLog($emailSucMsg, ['attributes' => $producer->attributes]);

                        $producer->token = password_hash($producer->token, PASSWORD_BCRYPT, ['cost' => 10, 'salt' => Yii::$app->params['staticSalt']]);
                        $producer->tokenSentAt = Yii::$app->util->getUtcDateTime();
                        //$success = false;
                        $success = $producer->saveModel();
                        //echo "\n<br />success:" . $success;
                        if ($success) {
                            Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $producer->attributes]);
                        } else {
                            Yii::$app->appLog->writeLog($errMsg, ['errors' => $producer->errors, 'attributes' => $producer->attributes]);
                        }

                    } else {
                        Yii::$app->appLog->writeLog($emailErrMsg, ['attributes' => $producer->attributes]);
                    }

                    sleep(1);


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
