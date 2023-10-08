<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\controllers\BaseController;

class SiteController extends BaseController
{
    public $layout = 'main-admin';  // Main layout for super admin panel

    public function behaviors()
    {
        return [
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function allowed()
    {
        return [
            'Site.Login',
            'Site.Error',
            'Site.AccessDenied',
            'Site.Logout',
            'Site.Captcha',
            'Site.Index',
            'Site.Home',
            'Site.Unsubscribe'
        ];
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->appLog->writeLog('Login success');
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAccessDenied()
    {
        return $this->render('accessDenied', []);
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {

            if (Yii::$app->user->can('Dashboard.AdminDashboard')) {
                return $this->redirect(['dashboard/admin-dashboard']);
            } else if (Yii::$app->user->can('Dashboard.BailiffDashboard')) {
                return $this->redirect(['dashboard/bailiff-dashboard']);
            } else {
                return $this->redirect(['site/home']);
            }
        } else {
            return $this->redirect(['login']);
        }
    }

    public function actionLogout()
    {
        Yii::$app->appLog->writeLog('Logout success');
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    public function actionHome()
    {
        return $this->render('home', []);
    }

    /**
     * Unsubscribe from email
     */
    public function actionUnsubscribe()
    {
        $this->layout = 'guest';
        return $this->render('unsubscribe', []);
    }

    /**
     * @return bool
     */
    public function actionChangeLanguage(){
        $this->enableCsrfValidation = true;
        if(Yii::$app->request->isAjax){

            $languageId = Yii::$app->request->post('languageId');
            Yii::$app->session->set('languageId', $languageId);
            return true;
        } else {

            return false;
        }
    }

}
