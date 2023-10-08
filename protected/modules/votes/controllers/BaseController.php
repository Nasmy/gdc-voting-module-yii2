<?php

namespace app\modules\votes\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\BaseInflector;
use app\models\Category;

/**
 * All other controllers get extended from this controller. Common controller activities can be
 * defined here
 */
class BaseController extends Controller
{

    public $layout = 'main-voter'; // Main layout for super admin panel

    public function allowed()
    {
        return array();
    }

    public function beforeAction($action)
    {
        //die();
        parent::beforeAction($action); //Checks the CSRF validations

        $currentLanguageId = Yii::$app->session->get('languageId');
        Yii::$app->language = ($currentLanguageId) ? $currentLanguageId : Yii::$app->params['defaultLanguage'];

        Yii::$app->appLog->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        Yii::$app->appLog->email = is_object(Yii::$app->user->identity) ? Yii::$app->user->identity->email : '';

        $moduleId = BaseInflector::camelize(Yii::$app->controller->module->id);
        $controllerId = BaseInflector::camelize(Yii::$app->controller->id);
        $actionId = BaseInflector::camelize(Yii::$app->controller->action->id);

        //$permissionName = "{$moduleId}.{$controllerId}.{$actionId}"; // TODO: Module based RBAC
        $permissionName = "{$controllerId}.{$actionId}";
        $allowedActions = Yii::$app->controller->allowed();

        if (Yii::$app->getUser()->isGuest &&
                !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions)) &&
                Yii::$app->getRequest()->url !== Url::to(Yii::$app->getUser()->loginUrl)
        ) {

            //Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
            // TODO: Find the error on the above code
            header('Location:' . Url::to(Yii::$app->getUser()->loginUrl));
            die();
        }

        if (!Yii::$app->getUser()->isGuest &&
                !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions))) {

            if (!Yii::$app->user->can($permissionName)) {

                //print_r(Yii::$app->user->identity); die();
                Yii::$app->getResponse()->redirect(Yii::$app->params['votersAccessDeniedUrl']);
            }
        }
        return true;
    }

    protected function setCategoriesForHeader()
    {
        //Get award categories set it to the voter layout
        $categories = Category::find()->orderBy('order')->all();
        $this->view->params['awardCategories'] = $categories;
    }

}
