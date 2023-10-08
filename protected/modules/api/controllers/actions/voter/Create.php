<?php

namespace app\modules\api\controllers\actions\voter;

use Yii;
use yii\base\Action;
use app\modules\api\components\Message;
use app\models\Voter;
use app\modules\api\components\ApiStatusMessage;
use yii\base\Exception;

class Create extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        Yii::$app->appLog->writeLog('Request Params '.json_encode($params));

        $voter = new Voter();
        $voter->scenario = Voter::SCENARIO_API_CREATE;
        $voter->attributes = $params;
        $voter->loadDefaultValues();
        $voter->roleName = Voter::ROLE_NAME;

        try {
            if ($voter->saveModel()) {
                $statusCode = ApiStatusMessage::SUCCESS;
            }
            Yii::$app->appLog->writeLog('Error on saving');
        } catch (Exception $e) {
            $statusCode = ApiStatusMessage::FAILED;
            Yii::$app->appLog->writeLog(Yii::t('app', 'Voter save failed.'), ['exception' => $e->getMessage(), 'attributes' => $voter->attributes]);
        }

        $statusCode = !empty($voter->statusCode) ? $voter->statusCode : $statusCode;
        $statusMsg = !empty($voter->statusMessage) ? $voter->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);
        $response = Message::detailResponse($commonStatus);

        $this->controller->sendResponse($response);
    }
}