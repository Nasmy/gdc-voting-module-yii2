<?php

namespace app\modules\api\controllers\actions\voter;

use Yii;
use yii\base\Action;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Vote extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);
        //echo "\n<br>params = ";
        //print_r($params);

        $success = false;
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;
        $data = [];

        //echo "\n<br>user = ";
        //print_r(Yii::$app->user);
        $voterId = Yii::$app->user->identity->id;
        $voterName = Yii::$app->user->identity->name;
        //echo "\n<br>voterId = " . $voterId;
        $votes = $params['votes'];
        //echo "\n<br>votes = ";
        //print_r($votes);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($votes as $key => $arr) {
                //echo "\n<br>key = " . $key;
                //echo "\n<br>arr = ";
                //print_r($arr);
                // Voter vote save
                $vote = new \app\models\Vote();
                $vote->loadDefaultValues();
                $vote->voterId = $voterId;
                $vote->createdById = $voterId;
                $vote->createdBy = $voterName;
                $vote->categoryId = $arr['categoryId'];
                $vote->nomineeId = $arr['nomineeId'];

                $success = $vote->saveModel();
                if (!$success) {
                    break;
                }
            }

            // Voter save
            $voter = \app\models\Voter::findOne($voterId);
            $voter->voted = \app\models\Voter::VOTED_YES;
            $voter->votedAt = Yii::$app->util->getUtcDateTime();
            $success = $voter->saveModel();

        } catch (Exception $e) {
            $success = false;
            $statusCode = ApiStatusMessage::FAILED;
            Yii::$app->appLog->writeLog(Yii::t('app', 'Voter votes save failed.'), ['exception' => $e->getMessage(), 'attributes' => $vote->attributes]);
        }

        if ($success) {
            $statusCode = ApiStatusMessage::SUCCESS;
            $transaction->commit();
            Yii::$app->appLog->writeLog('Commit transaction.');
        } else {
            $transaction->rollBack();
            Yii::$app->appLog->writeLog('Rollback transaction.');
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);
        $response = Message::detailResponse($commonStatus);

        $this->controller->sendResponse($response);
    }
}