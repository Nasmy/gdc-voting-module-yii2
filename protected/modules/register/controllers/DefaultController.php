<?php

namespace app\modules\register\controllers;

use Yii;
use yii\helpers\Url;
use app\models\ProducerLoginForm;

/**
 * Default controller for the `producer` module
 */
class DefaultController extends BaseController
{

    public $layout = 'login'; // Main layout for producer section

    /**
     * Allowed actions for public users who has no login
     */

    public function allowed()
    {
        return [
            'Default.Index',
            'Default.Login',
            'Default.Error',
            'Default.ChangeLanguage'
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['default/login']);
    }

    /**
     * Login the Producer by the token which is sent as the query string
     * Old (Load the login screen for Producers to enter the token which is sent by an email)
     * @return string
     */
    public function actionLogin()
    {
       
		$now = strtotime('now') + (60 * 60);
		$endTime = strtotime(Yii::$app->params['registrationEndDateTime']);

		if ($endTime < $now) {
			$errMsg = Yii::t('app', 'The 11th The Globes de Crystal ceremony votes closed on January 12th at midnight. You can no longer access votes');
			Yii::$app->session->setFlash('error', $errMsg);
			Yii::$app->appLog->writeLog($errMsg, ['errorDetails' => \GuzzleHttp\json_encode(Yii::$app->request->get())]);
			return $this->render('invalid-token', [
				'errorMessage' => $errMsg,
			]);
			die();
		}

		$sucMsg = Yii::t('app', 'Producer login success');
		$errMsg = Yii::t('app', 'Invalid Token');

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $token=Yii::$app->request->get('token');
        if (Yii::$app->user->identity && Yii::$app->user->can('Dashboard.Index')) {
            Yii::$app->session->set('producerId','1234');

            if (isset($params['step'])) {

                $step = $params['step'];
                $this->redirect(Url::to([
                    'category/view-by-step/',
                    'step' => $step
                ]));
            } else {

                $this->redirect(['dashboard/index']);
            }


        } else {

			
            $producerLogin = new ProducerLoginForm();

            $this->layout = 'plain'; // Set the layout for invalid login		
			

            if (isset($params['token'])) {
				//echo "\n<br />token: " . $params['token'];
                $token = $params['token'];
                $decodedToken = $this->getDecodedToken($token);


                $loginFormArr = [
                    'ProducerLoginForm' => [
                        'token' => $decodedToken
                    ],
                ];

                if ($producerLogin->load($loginFormArr) && $producerLogin->login()) {
					//echo "\n<br />If";

                    Yii::$app->appLog->writeLog($sucMsg, ['hashedToken' => $producerLogin->getHashedToken(), 'producerId' => Yii::$app->user->identity->id]);

                    // Custom - Begin
                    if (isset($params['step'])) {

                        $step = $params['step'];
                        $this->redirect(Url::to([
                            'category/view-by-step/',
                            'step' => $step
                        ]));
                    } else {
						//echo "\n<br />Else";
                        $this->redirect(['dashboard/index']);
                    }


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


    }


    
    /**
     * @param $token
     * @return string
     */
    public function getDecodedToken($token)
    {


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
        $sucMsg = Yii::t('app', 'producer log out successfully');
        Yii::$app->appLog->writeLog($sucMsg, ['producer ID' => Yii::$app->user->identity->id]);

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
        $this->layout = 'main-producer';
        return $this->render('access-denied');
    }

    /**
     * Renders the error view
     * @return string
     */
    public function actionError()
    {
        $this->layout = 'main-producer';
        return $this->render('error');
    }

    /**
     * Renders the not-found (404) view
     * @return string
     */
    public function actionNotFound()
    {
        $this->layout = 'main-producer';
        return $this->render('not-found');
    }

}
