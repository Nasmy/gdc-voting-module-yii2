<?php

namespace app\modules\votes\controllers;

use Yii;
use yii\helpers\Url;
use app\models\IndexLoginForm;

/**
 * Index controller for the `voter` module
 */
class IndexController extends BaseController
{

    public $layout = 'login'; // Main layout for voter section

    /**
     * Allowed actions for public users who has no login
     */

    public function allowed()
    {
        return [
            'Index.Index',
            'Index.Login',
            'Index.Error',
            'Index.ChangeLanguage'
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //return $this->render('login');
        $this->redirect(['default/login']);
    }

    /**
     * Login the Voter by the token which is sent as the query string
     * Old (Load the login screen for voters to enter the token which is sent by an email)
     * @return string
     */
    public function actionLogin()
    {
		// Yohan
		/*
		if (isset($_GET['token']) && $_GET['token'] == 'MTM4NjUwTzY0TUEzQkJrdWZHQUtSc2NhWFZSWUwzcHNtVQ==') {
			echo "\n<br>Yohan";
			
			$now = strtotime('now');			
			echo "\n<br>now: " . $now;
			echo "\n<br>now: " . date('Y-m-d H:i:s', $now);
			$endTime = strtotime(Yii::$app->params['votingEndDateTime']);
			echo "\n<br>endTime: " . $endTime;
			echo "\n<br>endTime: " . date('Y-m-d H:i:s', $endTime);

			if ($endTime < $now) {
				$errMsg = Yii::t('app', 'The 11th The Globes de Crystal ceremony votes closed on January 4th at midnight. You can no longer access votes');
				Yii::$app->session->setFlash('error', $errMsg);
				Yii::$app->appLog->writeLog($errMsg, ['errorDetails' => \GuzzleHttp\json_encode(Yii::$app->request->get())]);
				return $this->render('invalid-token', [
					'errorMessage' => $errMsg,
				]);
				//die();
			}
			
		}
		*/
	
		/*	
		$now = strtotime('now');					
		$endTime = strtotime(Yii::$app->params['votingEndDateTime']);

		if ($endTime < $now) {
			$errMsg = Yii::t('app', 'The 11th The Globes de Crystal ceremony votes closed on January 4th at midnight. You can no longer access votes');
			Yii::$app->session->setFlash('error', $errMsg);
			Yii::$app->appLog->writeLog($errMsg, ['errorDetails' => \GuzzleHttp\json_encode(Yii::$app->request->get())]);
			return $this->render('invalid-token', [
				'errorMessage' => $errMsg,
			]);
			die();
		}
		*/
		
		//if (isset($_GET['token']) && $_GET['token'] == 'NDIyNVg1UEtTT0EzQkJrdWZHQUtSc2NhWFZSWUwzcHNtVQ==') {			
			//echo "\n<br>token 1: " . $_GET['token'];
		//}
			

		$sucMsg = Yii::t('app', 'Voter login success');
		$errMsg = Yii::t('app', 'Invalid Token');

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));
		
        if (Yii::$app->user->identity && Yii::$app->user->can('Dashboard.Index')) {

			//echo "\n<br />If";
			//echo "\n<br />token: " . $params['token'];			
            // Custom - Begin
            if (isset($params['step'])) {
				//echo "\n<br />If";
				//echo "\n<br />step: " . $params['step'];
                $step = $params['step'];
                $this->redirect(Url::to([
                    'category/view-by-step/',
                    'step' => $step
                ]));
            } else {
				//echo "\n<br />Else";				
                $this->redirect(['dashboard/index']);
            }
            // Custom - End

            //$this->redirect(['dashboard/index']);
			//exit;

        } else {
			
			//echo "\n<br />Else";
			

            $indexLogin = new IndexLoginForm();

            $this->layout = 'plain'; // Set the layout for invalid login		
			

            if (isset($params['token'])) {
				echo "\n<br />token: " . $params['token'];
                $token = $params['token'];
                $decodedToken = $this->getDecodedToken($token);
				echo "\n<br />decodedToken: " . $decodedToken;
				
				
				$loginFormArr = [
                    'IndexLoginForm' => [
                        'token' => $decodedToken
                    ],
                ];

                if ($indexLogin->load($loginFormArr) && $indexLogin->login()) {
					//echo "\n<br />If";

                    Yii::$app->appLog->writeLog($sucMsg, ['hashedToken' => $indexLogin->getHashedToken(), 'voterId' => Yii::$app->user->identity->id]);

                    // Custom - Begin
                    if (isset($params['step'])) {
						//echo "\n<br />If";
						//echo "\n<br />step: " . $params['step'];
                        $step = $params['step'];
                        $this->redirect(Url::to([
                            'category/view-by-step/',
                            'step' => $step
                        ]));
                    } else {
						//echo "\n<br />Else";
                        $this->redirect(['dashboard/index']);
                    }
                    // Custom - End

                    //$this->redirect(['dashboard/index']);

                } else {
					//echo "\n<br />Else";
                    Yii::$app->session->setFlash('error', $errMsg);
                    Yii::$app->appLog->writeLog($errMsg, ['errorDetails' => \GuzzleHttp\json_encode(Yii::$app->request->get())]);
                }
            }			
			//exit;

            return $this->render('invalid-token', [
				'errorMessage' => $errMsg,
            ]);
        }

        /*
        $this->layout = 'login'; //Set the main layout as login
        //Enable the following code to submit the token by post method
        if (Yii::$app->request->isPost) {

            if ($indexLogin->load(Yii::$app->request->post()) && $indexLogin->login()) {
                Yii::$app->appLog->writeLog($sucMsg, ['Token' => $indexLogin->getHashedToken(), 'Voter ID' => Yii::$app->user->identity->id]);
                $this->redirect(['dashboard/index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
                Yii::$app->appLog->writeLog($errMsg, ['Details' => \GuzzleHttp\json_encode(Yii::$app->request->post())]);
            }
        }

        return $this->render('login', [
			'model' => $indexLogin,
        ]);
        */
    }

    /**
     * @param $token
     * @return string
     */
    public function getDecodedToken($token)
    {
        /*
        $encode = urlencode(base64_encode($token));
        $decode = base64_decode(urldecode($encode));
        echo '<br>' . $encode;
        echo '<br>' . $decode;
        echo '<br>' . substr($decode, 0, 10);
        die();
        */

        $urlDecoded = urldecode($token);
        $base64Decoded = base64_decode($urlDecoded);
        return substr($base64Decoded, 0, 10);
    }

    /**
     * @return bool
     */
    public function actionChangeLanguage()
    {
        $this->enableCsrfValidation = true;
        if (Yii::$app->request->isAjax) {
            $languageId = Yii::$app->request->post('languageId');
            Yii::$app->session->set('languageId', $languageId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout($completed = null)
    {
        $sucMsg = Yii::t('app', 'Voter log out successfully');
        Yii::$app->appLog->writeLog($sucMsg, ['Voter ID' => Yii::$app->user->identity->id]);

        Yii::$app->user->logout(true);

        if ($completed == 1) {
            $sucMsg = Yii::t('app', 'Thank you for completing the voting process!');
            Yii::$app->session->setFlash('success', $sucMsg);
        }

        return $this->redirect(Yii::$app->getUser()->loginUrl);
    }

    /**
     * Renders the access denied (403) view
     * @return string
     */
    public function actionAccessDenied()
    {
        $this->layout = 'main-voter';
        return $this->render('access-denied');
    }

    /**
     * Renders the error view
     * @return string
     */
    public function actionError()
    {
        $this->layout = 'main-voter';
        return $this->render('error');
    }

    /**
     * Renders the not-found (404) view
     * @return string
     */
    public function actionNotFound()
    {
        $this->layout = 'main-voter';
        return $this->render('not-found');
    }

}
