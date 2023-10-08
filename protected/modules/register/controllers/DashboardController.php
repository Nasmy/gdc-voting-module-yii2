<?php

namespace app\modules\register\controllers;

use Yii;
use app\models\Category;
use yii\filters\VerbFilter;

/**
 * VoterController implements the CRUD actions for Voter model.
 */
class DashboardController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Load the dashboard components for the voters
     * @return string
     */
    public function actionIndex()
    {

        Yii::$app->appLog->writeLog('Dashboard viewed');

        $this->layout = 'main-voter'; // Main layout for voter section
        parent::setCategoriesForHeader(); // Set category list for layout header

        $category = Category::find()->orderBy('order ASC')->one(); // Get min order category

        // Check the session has current user id and email. If not don't allow to the dashboard
        if(Yii::$app->user->identity){
            return $this->render('index', [
                'minOrderCategory' => $category
            ]);
        } else {
            $this->redirect('login');
        }
    }

}
