<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Producer;
use app\models\ProducerSearch;
use app\models\Role;
use yii\data\Pagination;

/**
 * ProducerController implements the CRUD actions for Producer model.
 */
class ProducerController extends BaseController
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
     * Lists all Producer models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List producers');

        $searchModel = new ProducerSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Producer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View producer', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Producer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
    }

    /**
     * Updates an existing Producer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
	
    }

    /**
     * Deletes an existing Producer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        
    }

    /**
     * Lists all Producer models with Votes models.
     * @return mixed
     */
    public function actionRegister()
    {
        $this->layout = 'main-bailiff';

        Yii::$app->appLog->writeLog('List producers votes');
        $searchModel = new ProducerSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $searchRegisterResults = $searchModel->searchRegister($params);

        $dataProvider = $searchRegisterResults[0];
        $producersSearchCount = $searchVotesResults[1];
        
        $pagination = new Pagination(['totalCount' => $producersSearchCount, 'pageSize'=>10]);

        //Begin and end search count
        $defaultPageLimit = Yii::$app->params['defaultPaginationLimit'];
        if(isset($params['page']) && isset($params['per-page'])){
            $beginCount = (($params['page']-1) * $params['per-page']);//Begin count calculated by pagination and page limit
            $endCount = ($producersSearchCount < ($defaultPageLimit*$params['page'])) ? $producersSearchCount : $beginCount + $params['per-page'];//Set the end count by increasing the per page
            $beginCount = $beginCount+1;//Allways make the begin the start with 1 increased
        } else {
            $beginCount = 1;
            $endCount = ($producersSearchCount < $defaultPageLimit) ? $producersSearchCount : Yii::$app->params['defaultPaginationLimit'];
        }

        return $this->render('votes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
            'totalResultCount' => $producersSearchCount,
            'beginCount' => $beginCount,
            'endCount' => $endCount
        ]);
    }

    /**
     * Finds the producer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Producer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model =Producer::findOne($id);
        if (null !== $model) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }
	
	public function actionNotify() {
      
        $cmd = "php protected/yii " . "notifyproducer/send-remider-test >> protected/runtime/preseelog/send_press_release_2019_09_15.log";

        exec($cmd, $output, $status); // For Linux
        print_r($output);
    }

    public function actionPresee() {
        return $this->render('/email-template/presseTemplate.php');
    }
}
