<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Permission;
use app\models\PermissionSearch;

/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class PermissionController extends BaseController
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
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List permissions.');

        $searchModel = new PermissionSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View permission.', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->appLog->writeLog('Create permission.');

        $sucMsg = Yii::t('app', 'Permission saved successfully.');
        $errMsg = Yii::t('app', 'Permission save failed.');
        $success = false;
        $model = new Permission();

        if (Yii::$app->request->post()) {
            $model->loadDefaultValues();
            $model->load(Yii::$app->request->post());
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
        ]);
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->appLog->writeLog('Update permission.');

        $sucMsg = Yii::t('app', 'Permission updated successfully.');
        $errMsg = Yii::t('app', 'Permission update failed.');
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
        ]);
    }

    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->appLog->writeLog('Delete permission.');

        $sucMsg = Yii::t('app', 'Permission deleted successfully.');
        $errMsg = Yii::t('app', 'Permission delete failed.');
        $success = false;
        $model = $this->findModel($id);
        $success = $model->deleteModel();

        if ($success) {
            Yii::$app->session->setFlash('success', $sucMsg);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', $errMsg);
        }
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Permission::findOne($id);
        if (null !== $model) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist.', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
