<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\BaseInflector;
use app\models\AuthVoter;
use app\models\Voter;

/**
 * All other controllers get extended from this controller. Common controller activities can be
 * defined here
 */
class BaseController extends Controller
{
    public $layout = 'main-admin';//Main layout for super admin panel
    public function allowed()
    {
        return array();
    }

    public function beforeAction($action)
    {
        //Yii::$app->language = 'fr';

        Yii::$app->appLog->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        Yii::$app->appLog->email = is_object(Yii::$app->user->identity) ? Yii::$app->user->identity->email : '';

        $controllerId = BaseInflector::camelize(Yii::$app->controller->id);
        $actionId = BaseInflector::camelize(Yii::$app->controller->action->id);
        $permissionName = "{$controllerId}.{$actionId}";
        $allowedActions = Yii::$app->controller->allowed();

        if (Yii::$app->getUser()->isGuest &&
            !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions)) &&
            Yii::$app->getRequest()->url !== Url::to(Yii::$app->getUser()->loginUrl)
        ) {
            Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
        }

        if (!Yii::$app->getUser()->isGuest &&
            !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions))) {
            if (!Yii::$app->user->can($permissionName)) {
                //print_r(Yii::$app->user->identity); die();
                Yii::$app->getResponse()->redirect(Yii::$app->params['accessDeniedUrl']);
            }
        }
        return true;
    }

    /**
     * Authenticate request.
     * @param string $apiKey API key
     * @param string $apiSecret API secret
     * @param string $userToken User access token
     * @param string $action Current action
     * @return bool true of false
     */
    private function authenticate($apiKey, $apiSecret, $userToken, $action)
    {
        $voter = Voter::find()->where('userToken = :userToken', [':userToken' => $userToken])->one();
        Yii::$app->user->login(AuthVoter::findByToken($voter->token));
        Yii::$app->appLog->email = $user->email;
    }
}

?>