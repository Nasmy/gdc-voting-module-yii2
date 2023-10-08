<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\Nominee;
use app\models\NomineeSearch;

/**
 * NomineeController implements the CRUD actions for Nominee model.
 */
class NomineeController extends BaseController
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
     * Lists all Nominee models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List nominees');

        $searchModel = new NomineeSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Nominee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View nominee', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Nominee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->appLog->writeLog('Create nominee');

        $sucMsg = Yii::t('app', 'Nominee saved successfully');
        $errMsg = Yii::t('app', 'Nominee save failed');
        $imgSucMsg = Yii::t('app', 'Image save successfully');
        $imgErrMsg = Yii::t('app', 'Image save failed');
        $success = false;
        $image = null;

        $model = new Nominee();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            //echo "\n<br />model: ";
            //print_r($model);
            $image = UploadedFile::getInstance($model, 'image');
            //echo "\n<br />image: ";
            //print_r($image);
            $model->imageName = $image->name;
            $model->image = $image;
            $model->setImageExt();
            $model->setCustomImageName();
            $model->setImageSrcPath();
            $model->setImageWebPath();

            //echo "\n<br />model: ";
            //print_r($model);
            //echo "\n<br />imageSrcPath: " . $model->imageSrcPath;
            //exit;

            $transaction = Yii::$app->db->beginTransaction();
            try {

                $success = $model->saveModel();
                if ($success) {

                    Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $model->attributes]);

                    $success = $image->saveAs($model->imageSrcPath);
                    if ($success) {
                        Yii::$app->appLog->writeLog($imgSucMsg, ['image' => $image]);
                    } else {
                        Yii::$app->appLog->writeLog($imgErrMsg, ['errors' => $image->errors, 'image' => $image]);
                    }
                } else {
                    Yii::$app->appLog->writeLog($errMsg, ['errors' => $model->errors, 'attributes' => $model->attributes]);
                }
            } catch (Exception $e) {
                $success = false;
                Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
            }

            if ($success) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Rollback');
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Nominee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->appLog->writeLog('Update nominee', ['id' => $id]);

        $sucMsg = Yii::t('app', 'Nominee updated successfully');
        $errMsg = Yii::t('app', 'Nominee update failed');
        $imgSucMsg = Yii::t('app', 'Image updated successfully');
        $imgErrMsg = Yii::t('app', 'Image update failed');
        $success = false;
        $image = null;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            //echo "\n<br />model: ";
            //print_r($model);
            $image = UploadedFile::getInstance($model, 'image');
            //echo "\n<br />image: ";
            //print_r($image);

            if (!empty($image)) {
                $model->imageName = $image->name;
                $model->image = $image;
                $model->setImageExt();
                $model->setCustomImageName();
                $model->setImageSrcPath();
                $model->setImageWebPath();
            }
            //echo "\n<br />model: ";
            //print_r($model);
            //exit;

            $transaction = Yii::$app->db->beginTransaction();
            try {

                $success = $model->saveModel();
                if ($success) {

                    Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $model->attributes]);
                    if (!empty($image)) {

                        $success = $image->saveAs($model->imageSrcPath);
                        if ($success) {
                            Yii::$app->appLog->writeLog($imgSucMsg, ['image' => $image]);
                        } else {
                            Yii::$app->appLog->writeLog($imgErrMsg, ['errors' => $image->errors, 'image' => $image]);
                        }
                    }
                } else {
                    Yii::$app->appLog->writeLog($errMsg, ['errors' => $model->errors, 'attributes' => $model->attributes]);
                }
            } catch (Exception $e) {
                $success = false;
                Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $image]);
            }

            if ($success) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Rollback');
                Yii::$app->session->setFlash('error', $errMsg);
            }
        } else {
            $model->setImageWebPath();
            $model->image = $model->imageWebPath;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Nominee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sucMsg = Yii::t('app', 'Nominee deleted successfully');
        $errMsg = Yii::t('app', 'Nominee delete failed');
        $imgSucMsg = Yii::t('app', 'Image deleted successfully');
        $imgErrMsg = Yii::t('app', 'Image delete failed');
        $success = false;

        // Nominee
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $success = $model->delete();
            if ($success) {
                Yii::$app->appLog->writeLog($sucMsg, ['attributes' => $model->attributes]);

                $success = file_exists($model->imageSrcPath);
                if ($success) {

                    $success = unlink($model->imageSrcPath);
                    if ($success) {
                        Yii::$app->appLog->writeLog($imgSucMsg, ['image' => $model->imageSrcPath]);
                    } else {
                        Yii::$app->appLog->writeLog($imgErrMsg, ['errors' => $caseFile->errors, 'image' => $model->imageSrcPath]);
                    }
                } else {
                    $success = false; // Allow user to continue though file could not be find on physical path.
                    Yii::$app->appLog->writeLog($imgErrMsg . '. ' . 'Image could not find in the system', ['image' => $model->imageSrcPath]);
                }
            } else {
                Yii::$app->appLog->writeLog($errMsg, ['attributes' => $model->attributes]);
            }
        } catch (Exception $e) {
            $success = false;
            Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage(), 'attributes' => $model->attributes, 'image' => $model->imageSrcPath]);
        }

        if ($success) {
            $transaction->commit();
            Yii::$app->session->setFlash('success', $sucMsg);
            return $this->redirect(['index']);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $transaction->rollBack();
            Yii::$app->appLog->writeLog('Rollback');
            Yii::$app->session->setFlash('error', $errMsg);
        }

        return $this->redirect(['index']);
    }

    /**
     * Lists Nominee votes.
     * @return mixed
     */
    public function actionVotes()
    {
        $this->layout = 'main-bailiff';

        Yii::$app->appLog->writeLog('List nominee votes');

        $searchModel = new NomineeSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        // Added to set the current categoryId and nomineeId for the export menu widget
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->queryParams;
        }

        $dataProvider = $searchModel->searchVotes($params);

        return $this->render('votes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Nominee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Nominee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Nominee::findOne($id);
        if (null !== $model) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }

}
