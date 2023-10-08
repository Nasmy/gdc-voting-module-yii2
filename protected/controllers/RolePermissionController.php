<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RolePermission;
use app\models\RolePermissionSearch;

/**
 * RolePermissionController implements the CRUD actions for RolePermission model.
 */
class RolePermissionController extends BaseController
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
     * Lists all RolePermission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolePermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RolePermission model.
     * @param string $roleName
     * @param string $permissionName
     * @return mixed
     */
    public function actionView($roleName, $permissionName)
    {
        return $this->render('view', [
                    'model' => $this->findModel($roleName, $permissionName),
        ]);
    }

    /**
     * Creates a new RolePermission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RolePermission();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'roleName' => $model->roleName, 'permissionName' => $model->permissionName]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RolePermission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $roleName
     * @param string $permissionName
     * @return mixed
     */
    public function actionUpdate($roleName, $permissionName)
    {
        $model = $this->findModel($roleName, $permissionName);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'roleName' => $model->roleName, 'permissionName' => $model->permissionName]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RolePermission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $roleName
     * @param string $permissionName
     * @return mixed
     */
    public function actionDelete($roleName, $permissionName)
    {
        $this->findModel($roleName, $permissionName)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RolePermission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $roleName
     * @param string $permissionName
     * @return RolePermission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($roleName, $permissionName)
    {
        if (($model = RolePermission::findOne(['roleName' => $roleName, 'permissionName' => $permissionName])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
