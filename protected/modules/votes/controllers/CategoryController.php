<?php

namespace app\modules\votes\controllers;

use app\models\Voter;
use Yii;
use app\models\Vote;
use app\models\Category;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BaseController
{

    public $layout = 'main-voter'; // Main layout for voter section

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
        Yii::$app->appLog->writeLog('Category index view');

        $category = new Category();
        $minOrderCat = $category->find()->orderBy('order')->one();
        $this->redirect( Url::to([
            'category/view-by-step/',
            'step' => $minOrderCat->id
        ]));
    }

    /**
     * Displays a single Category model by step(order) id.
     * @param integer $step (order)
     * @return mixed
     */
    public function actionViewByStep()
    {

        $sucMsg = Yii::t('app', 'Category step enter successfully');
        $errMsg = Yii::t('app', 'Invalid step');

        $category = new Category();
        $activeQuery = $category->find()->orderBy('order');
        $categoryOrderObj = $activeQuery->asArray()->all();
        $categoryOrderArr = ArrayHelper::getColumn($categoryOrderObj, 'order');

        //Get the order/step from query string
        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));

        $step = null;
        if(isset($params['step'])){
            $step = $params['step'];
        }

        if (ArrayHelper::isIn($step, $categoryOrderArr)) {

            parent::setCategoriesForHeader(); //Set category list for layout header
            $this->view->params['step'] = $step; //Set the current step to the voter layout

            $categoryRow = $category->findOne(['order' => $step]); //Get the category model by order number

            $categoryOrderArrKey = array_search($step, $categoryOrderArr);
            $minCategoryOrderArrKey = min(array_keys($categoryOrderArr));
            $maxCategoryOrderArrKey = max(array_keys($categoryOrderArr));

            //Simple logic to maintain prev, next orders
            $prevOrder = 0;
            $nextOrder = 0;

            if ($categoryOrderArrKey === $minCategoryOrderArrKey) {
                $nextOrder = $categoryOrderArr[$categoryOrderArrKey + 1];
            } else if ($categoryOrderArrKey === $maxCategoryOrderArrKey) {
                $prevOrder = $categoryOrderArr[$categoryOrderArrKey - 1];
            } else {
                $prevOrder = $categoryOrderArr[$categoryOrderArrKey - 1];
                $nextOrder = $categoryOrderArr[$categoryOrderArrKey + 1];
            }

            $voter = new Voter();
            $voterModel = $voter->findOne(['id' => Yii::$app->user->identity->id]);
            $voterVoted = $voterModel->getVotes()->all();
            $voterCatArr = ArrayHelper::getColumn($voterVoted, 'categoryId');

            Yii::$app->appLog->writeLog($sucMsg, ['voterId' => Yii::$app->user->identity->id, 'attributes' => $categoryRow->attributes]);

            return $this->render('view', [
                'model' => $categoryRow,
                'awardCategories' => $category->find()->orderBy('order')->all(),
                'existVote' => $categoryRow->getVotes()->where(['voterId' => Yii::$app->user->identity->id])->one(),
                'votedCategories' => $voterCatArr,
                'prevOrder' => $prevOrder,
                'nextOrder' => $nextOrder,
                'maxOrder' => $categoryOrderArr[$maxCategoryOrderArrKey],
            ]);
        } else {
            Yii::$app->appLog->writeLog($errMsg, ['voterId' => Yii::$app->user->identity->id, 'step' => $step]);
            Yii::$app->session->setFlash('error', $errMsg);
            return $this->render('not-found');
        }
    }

    /**
     * Main function for adding votes for nominees
     * @return boolean
     */
    public function actionVote()
    {
        $sucMsg = Yii::t('app', 'Vote saved successfully.');
        $sucDelMsg = Yii::t('app', 'Vote deleted successfully.');
        $errMsg = Yii::t('app', 'Vote save failed.');
        $errDelMsg = Yii::t('app', 'Vote delete failed.');

        $this->enableCsrfValidation = true;
        if (Yii::$app->request->isAjax) {

            $vote = new Vote();

            $nomineeId = Yii::$app->request->post('nomineeId');
            $categoryId = Yii::$app->request->post('categoryId');
            $voterId = Yii::$app->user->identity->id;
            $voterName = Yii::$app->user->identity->name;
            $success = false;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                //Check the request for remove existing vote record
                $existSameVote = $vote->find()->where(['categoryId' => $categoryId, 'voterId' => $voterId, 'nomineeId' => $nomineeId])->one();
                if ($existSameVote !== null) {

                    $modelExistSame = Vote::findOne($existSameVote->id);
                    $modelExistSame->delete();
                    Yii::$app->appLog->writeLog($sucDelMsg, ['voterId' => $voterId, 'voteId' => $existSameVote->id]);
                    $isSaved = Category::VOTE_DELETED;
                } else {
                    //Delete the previous selection for a particular category
                    $existVote = $vote->find()->where(['categoryId' => $categoryId, 'voterId' => $voterId])->one();
                    if ($existVote !== null) {
                        $modelExist = Vote::findOne($existVote->id);
                        $modelExist->delete();
                    }

                    $vote->nomineeId = $nomineeId;
                    $vote->categoryId = $categoryId;
                    $vote->voterId = $voterId;
                    $vote->createdById = $voterId;
                    $vote->createdBy = $voterName;

                    if($vote->saveModel()){
                        $isSaved = Category::VOTE_SAVED;
                        Yii::$app->appLog->writeLog($sucMsg, ['voterId' => $voterId, 'voteId' => $vote->id]);
                    }
                }

                $success = true;
            } catch (Exception $e) {
                $isSaved = Category::VOTE_ERROR;
                $success = false;
                Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage()]);
            }

            if ($success) {
                $transaction->commit();
                Yii::$app->appLog->writeLog('Commit.');
                Yii::$app->session->setFlash('success', $sucMsg);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Rollback.');
                Yii::$app->session->setFlash('error', $errMsg);
            }

            return $isSaved;
        }
    }

    /**
     * @return int
     */
    public function actionCheckIsVoted()
    {
        // TO DO: Need to validate csrf token
        $this->enableCsrfValidation = true;
        if (Yii::$app->request->isAjax) {
            $categoryId = Yii::$app->request->post('categoryId');
            $voterId = Yii::$app->user->identity->id;

            Yii::$app->appLog->writeLog('vote step verified', ['voterId' => $voterId, 'categoryId' => $categoryId]);

            $modelVote = new Vote();
            $isVoted = $modelVote->find()->where(['categoryId' => $categoryId, 'voterId' => $voterId])->one();
            if ($isVoted !== null) {
                return Category::VOTE_EXIST;
            } else {
                return Category::VOTE_NOT_EXIST;
            }
        }
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
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
