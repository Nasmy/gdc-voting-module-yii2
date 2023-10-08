<?php

namespace app\controllers;

use app\models\CategoryNominee;
use app\models\Nominee;
use app\models\Vote;
use Yii;
use app\models\Category;
use app\models\CategorySearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BaseController
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List categories');

        $searchModel = new CategorySearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View category.', ['id' => $id]);

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $sucMsg = Yii::t('app', 'Category save successfully');
        $errMsg = Yii::t('app', 'Category save failed');
        $success = false;

        $category = new Category();

        if ($category->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            try {

                $success = $category->saveModel();
                if ($success) {

                    foreach ($category->nomineeListArr as $nominee) {
                        $categoryNominee = new CategoryNominee();
                        $categoryNominee->categoryId = $category->id;
                        $categoryNominee->nomineeId = $nominee;
                        $success = $categoryNominee->saveModel();
                        if (!$success) {
                            break;
                        }
                    }
                }
            } catch (Exception $e) {
                $success = false;
                Yii::$app->appLog->writeLog('Category save failed', ['exception' => $e->getMessage(), 'attributes' => $category->attributes]);
            }

            if ($success) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Rollback.');
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('create', [
                    'model' => $category,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $sucMsg = Yii::t('app', 'Category updated successfully');
        $errMsg = Yii::t('app', 'Category update failed');
        $success = false;

        $category = $this->findModel($id);

        $nominees = $category->getNominees()->all();
        foreach ($nominees as $nominee) {
            $category->nomineeListArr[] = $nominee->id;
        }

        if ($category->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            try {

                // Delete existing nominees list for this category
                $categoryNominee = new CategoryNominee();
                $success = $categoryNominee->deleteAll(['categoryId' => $category->id]);
                if ($success) {
                    Yii::$app->appLog->writeLog('Category nominees deleted successfully', ['attributes' => $category->attributes]);

                    $success = $category->saveModel();
                    if ($success) {

                        foreach ($category->nomineeListArr as $nominee) {
                            $categoryNominee = new CategoryNominee();
                            $categoryNominee->categoryId = $category->id;
                            $categoryNominee->nomineeId = $nominee;
                            $success = $categoryNominee->saveModel();
                            if (!$success) {
                                break;
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $success = false;
                Yii::$app->appLog->writeLog('Category update failed', ['exception' => $e->getMessage(), 'attributes' => $category->attributes]);
            }

            if ($success) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Rollback.');
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('update', [
			'model' => $category,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sucMsg = Yii::t('app', 'Category deleted successfully');
        $errMsg = Yii::t('app', 'Category delete failed');

        $category = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // Delete existing nominees list before delete the category
            $categoryNominee = new CategoryNominee();
            if ($categoryNominee->find()->where(['categoryId' => $category->id])->exists()) { //TO DO - Do we really need to check this?
                $success = $categoryNominee->deleteAll(['categoryId' => $category->id]);
                if ($success) {
                    Yii::$app->appLog->writeLog('Category nominees deleted successfully', ['attributes' => $categoryNominee->attributes]);
                }
            }

            // Also delete the vote list for the current category
            $vote = new Vote();
            if ($vote->find()->where(['categoryId' => $category->id])->exists()) { //TO DO - Do we really need to check this?
                $success = $vote->deleteAll(['categoryId' => $category->id]);
                if ($success) {
                    Yii::$app->appLog->writeLog('Category votes deleted successfully', ['attributes' => $vote->attributes]);
                }
            }

            $success = $category->deleteModel();
            if ($success) {
                Yii::$app->appLog->writeLog('Category deleted successfully', ['attributes' => $category->attributes]);
            }
        } catch (Exception $e) {
            $success = false;
            Yii::$app->appLog->writeLog('Category delete failed', ['exception' => $e->getMessage(), 'attributes' => $category->attributes]);
        }

        if ($success) {
            $transaction->commit();
            Yii::$app->session->setFlash('success', $sucMsg);
        } else {
            $transaction->rollBack();
            Yii::$app->appLog->writeLog('Rollback.');
            Yii::$app->session->setFlash('error', $errMsg);
        }
        return $this->redirect(['index']);
    }

    /**
     * Show the detailed vote count and nominees for the selected category
     * @return string
     */
    public function actionDetailedVotes()
    {
        $this->layout = 'main-bailiff';

        $category = new Category();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        if ($params && Yii::$app->request->isGet) {
            $categoryId = $params['id'];
        } elseif ($params && Yii::$app->request->isPost) {
            $categoryId = $params[Category::tableName()]['id'];
        } else {
            $minOrderCategory = $category->find()->orderBy('order')->one();
            $categoryId = $minOrderCategory->id;
        }

        Yii::$app->appLog->writeLog('Detailed votes view', ['categoryId' => $categoryId]);

        $categories = $category->find()->orderBy('order')->all();
        $categoryRow = $category->find()->where(['id' => $categoryId])->one();

        return $this->render('view-detailed-votes', [
            'categoryRow' => $categoryRow,
            'categories' => $categories
        ]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Category::findOne($id);
        if (null !== $model) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }

}
