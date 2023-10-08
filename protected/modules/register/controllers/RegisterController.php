<?php

namespace app\modules\register\controllers;

use app\components\Mail;
use Yii;
use app\models\Category;
use app\models\Voter;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegisterController implements the CRUD actions for Register model.
 */
class RegisterController extends BaseController
{
    public $layout = 'main-producer'; //Main layout for voter

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
     * List voted summary
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProducerSummary()
    {

        parent::setCategoriesForHeader();

        Yii::$app->appLog->writeLog('Vote summary viewed');

        $voter = $this->findModel(Yii::$app->user->identity->id);
        $category = Category::find()->all();
        $maxOrder = Category::find()->orderBy('order DESC')->one(); //Get maximum order number

        $this->view->params['step'] = $maxOrder->order; //Overide the existing step with max category order

        return $this->render('votes-summary', [
            'model' => $voter,
            'categories' => $category,
            'maxOrderCategory' => $maxOrder,
        ]);
    }

    /**
     * Add voted field as 1 when voter confirm the voting process
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionProducerConfirm()
    {
        $sucMsg = Yii::t('app', 'Voter update successfully');
        $errMsg = Yii::t('app', 'Voter update failed');

        $this->enableCsrfValidation = true;
        if (Yii::$app->request->isAjax) {

            $success = false;
            $confirmed = Yii::$app->request->post('confirmed');
            $voterId = Yii::$app->user->identity->id;
            $transaction = Yii::$app->db->beginTransaction();

            try {
                //$browser = get_browser(null, true);

                $voter = $this->findModel($voterId);
                $voter->voted = ($confirmed == true) ? 1 : 0;
                /*
                $voter->browser = (isset($browser['browser'])) ? $browser['browser'] : '';
                $voter->browserVersion = (isset($browser['version'])) ? $browser['version'] : '';
                $voter->platform = (isset($browser['platform'])) ? $browser['platform'] : '';
                $voter->device = (isset($browser['device_type'])) ? $browser['device_type'] : '';
                */
                $voter->device = 'Desktop';
                $voter->votedAt = date('Y-m-d H:i:s');
                //$voter->updatedById = null;

                if ($voter->saveModel()) {
                    $success = true;
                    Yii::$app->appLog->writeLog($sucMsg, ['voterId' => $voterId]);
                }
            } catch (Exception $e) {
                $success = false;
                Yii::$app->appLog->writeLog($errMsg, ['exception' => $e->getMessage()]);
            }

            if ($success) {
                $this->sendVotingSummaryEmail($voter);
                $transaction->commit();
                Yii::$app->appLog->writeLog('Vote Confirm Commit.');
                Yii::$app->session->setFlash('success', $sucMsg);
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Vote Confirm Rollback.');
                Yii::$app->session->setFlash('error', $errMsg);
            }

            return $success;
        } else {
            return false;
        }
    }

    /**
     * Send an email with voted summary to registered email address
     * @return bool
     */
    public function sendVotingSummaryEmail($voter)
    {
        $category = new Category();
        $categories = $category->find()->all();

        $votedSummery = [];
        foreach ($categories as $category) {

            $emptyObj = new \stdClass();
            $emptyObj->name = $category->name;
            $votes = $category->getVotes()->where(['voterId' => $voter->id])->one();

            if (!empty($votes)) {
                $nominee = $votes->getNominee()->one();
                $emptyObj->value = $nominee->name;
            } else {

                $emptyObj->value = Yii::t('app', 'Not Voted');
            }

            $votedSummery[] = $emptyObj;
        }

        $voterEmail = $voter->email;
        $voterName = $voter->name;
        $voterGender = $voter->gender;
        //$adminEmails = Yii::$app->params['votingSummaryEmail'];
        //$emails = implode(',', $adminEmails) . ',' . $voterEmail;
		$emails = $voterEmail;

        $mail = new Mail();
        $success = false;
        try {
            if ($mail->sendVotingSummary($emails, $voterName, $voterGender, $votedSummery)) {
                $success = true;
            } else {
                $success = false;
            }
        } catch (Exception $e) {

            Yii::$app->appLog->writeLog('Send voting summary exception', ['exception' => $e->getMessage(), 'emails' => $emails, 'voter name' => $voterName]);
            $success = false;
        }

        if ($success) {

            Yii::$app->appLog->writeLog('Send voting summary success', ['emails' => $emails, 'voter name' => $voterName]);
            return true;
        } else {

            Yii::$app->appLog->writeLog('Send voting summary failed', ['emails' => $emails, 'voter name' => $voterName]);
            return false;
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionExit()
    {
        Yii::$app->appLog->writeLog('Vote completed', ['voterId' => Yii::$app->user->identity->id]);
        return $this->redirect(['default/logout', 'completed' => 1]);
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
        if (($model = Producer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
