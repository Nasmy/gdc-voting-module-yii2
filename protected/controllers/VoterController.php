<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Voter;
use app\models\VoterSearch;
use app\models\Role;
use yii\data\Pagination;

/**
 * VoterController implements the CRUD actions for Voter model.
 */
class VoterController extends BaseController
{
    public $layout = 'main-admin'; // Main layout for super admin panel

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
     * Lists all Voter models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List voters');

        $searchModel = new VoterSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Voter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View voter', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Voter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		// updated for safty purpose
        /*Yii::$app->appLog->writeLog('Create voter');

        $sucMsg = Yii::t('app', 'Voter saved successfully');
        $errMsg = Yii::t('app', 'Voter save failed');
        $success = false;
        $model = new Voter();

        if (Yii::$app->request->post()) {
            $model->loadDefaultValues();
            $model->load(Yii::$app->request->post());
            $model->roleName = Role::VOTER;
            $success = $model->saveModel();

            if ($success) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);*/
    }

    /**
     * Updates an existing Voter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		// updated for safty purpose
        /*Yii::$app->appLog->writeLog('Update voter.');

        $sucMsg = Yii::t('app', 'Voter updated successfully');
        $errMsg = Yii::t('app', 'Voter update failed');
        $success = false;
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $success = $model->saveModel();

            if ($success) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);*/
    }

    /**
     * Deletes an existing Voter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*Yii::$app->appLog->writeLog('Delete voter');

        $sucMsg = Yii::t('app', 'Voter deleted successfully');
        $errMsg = Yii::t('app', 'Voter delete failed');
        $success = false;
        $model = $this->findModel($id);
        $success = $model->deleteModel();

        if ($success) {
            Yii::$app->session->setFlash('success', $sucMsg);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', $errMsg);
        }*/
    }

    /**
     * Lists all Voter models with Votes models.
     * @return mixed
     */
    public function actionVotes()
    {
        $this->layout = 'main-bailiff';

        Yii::$app->appLog->writeLog('List voters votes');

        //$idFilter = Yii::$app->request->getQueryParam('id', null);
        //echo "\n<br>id: " . $idFilter;
        //exit;

        $searchModel = new VoterSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $searchVotesResults = $searchModel->searchVotes($params);

        $dataProvider = $searchVotesResults[0];
        $votersSearchCount = $searchVotesResults[1];
        
        $pagination = new Pagination(['totalCount' => $votersSearchCount, 'pageSize'=>10]);

        //Begin and end search count
        $defaultPageLimit = Yii::$app->params['defaultPaginationLimit'];
        if(isset($params['page']) && isset($params['per-page'])){
            $beginCount = (($params['page']-1) * $params['per-page']);//Begin count calculated by pagination and page limit
            $endCount = ($votersSearchCount < ($defaultPageLimit*$params['page'])) ? $votersSearchCount : $beginCount + $params['per-page'];//Set the end count by increasing the per page
            $beginCount = $beginCount+1;//Allways make the begin the start with 1 increased
        } else {
            $beginCount = 1;
            $endCount = ($votersSearchCount < $defaultPageLimit) ? $votersSearchCount : Yii::$app->params['defaultPaginationLimit'];
        }

        return $this->render('votes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
            'totalResultCount' => $votersSearchCount,
            'beginCount' => $beginCount,
            'endCount' => $endCount
        ]);
    }

    /**
     * Finds the Voter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Voter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Voter::findOne($id);
        if (null !== $model) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }
	
	public function actionNotify() {
      
        // $cmd = "php protected/yii " . "notify/send-final-reminder-to-voter";
        // $cmd = "php protected/yii " . "notify/send-first-reminder-to-voter >> protected/send_voter_token_2009_5.log";
		//$cmd = "php protected/yii " . "notify/send-first-reminder-to-voter >> protected/send_final_reminder_2019_1_14_5.log";
        // $cmd = "php protected/yii " . "notify/send-first-reminder-to-voter";
        // $cmd = "php protected/yii " . "notify/send-voter-token >> protected/send_voter_token_2019_25.log";
        $cmd = "php protected/yii " . "notify/send-remider-test >> protected/runtime/preseelog/send_press_release_2019_09_16.log";

        exec($cmd, $output, $status); // For Linux
        print_r($output);
    }

    public function actionPresee() {
        return $this->render('/email-template/presseTemplate.php');
    }
}
