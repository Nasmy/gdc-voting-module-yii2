<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List users.');

        $searchModel = new UserSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View user.', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->appLog->writeLog('Create user.');

        $sucMsg = Yii::t('app', 'User saved successfully.');
        $errMsg = Yii::t('app', 'User save failed.');
        $success = false;
        $model = new User();
        $model->timeZone = Yii::$app->params['defaultTimeZone'];

        if (Yii::$app->request->post()) {
            $model->loadDefaultValues();
            $model->load(Yii::$app->request->post());
            $model->password = $model->encryptPassword($model->formPassword);
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
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->appLog->writeLog('Update user.');

        $sucMsg = Yii::t('app', 'User updated successfully.');
        $errMsg = Yii::t('app', 'User update failed.');
        $success = false;
        $model = $this->findModel($id);
        $model->oldPassword = $model->password;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if (empty($model->formPassword)) {
                $model->password = $model->oldPassword;
            } else {
                $model->password = $model->encryptPassword($model->formPassword);
            }
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->appLog->writeLog('Delete user.');

        $sucMsg = Yii::t('app', 'User deleted successfully.');
        $errMsg = Yii::t('app', 'User delete failed.');
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
     * Update own profile details.
     * @return mixed
     */
    public function actionMyAccount()
    {
        $sucMsg = Yii::t('app', 'Profile updated successfully.');
        $errMsg = Yii::t('app', 'Profile update failed.');

        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('myAccount', [
            'model' => $model,
        ]);
    }

    /**
     * Change password.
     * @return mixed
     */
    public function actionChangePassword()
    {
        $sucMsg = Yii::t('app', 'Password changed successfully.');
        $errMsg = Yii::t('app', 'Password change failed.');

        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_CHANGE_PASSWORD;
        $model->oldPassword = $model->password;

        if ($model->load(Yii::$app->request->post())) {

            $oldPassword = '';
            if ('' != $model->oldPassword) {
                $oldPassword = $model->oldPassword;
                $model->oldPassword = User::getComparingPassword($model->formPassword, $model->oldPassword);
            }

            $model->password = $model->encryptPassword($model->newPassword);

            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['change-password']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }

            $model->oldPassword = $oldPassword;
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
