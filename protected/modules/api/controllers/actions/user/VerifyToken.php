<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\Verification;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class VerifyToken extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $model = new Verification();
        $model->scenario = Verification::SCENARIO_API_VERIFY;
        $model->attributes = $params;

        if ($model->validateModel()) {
            $dbModel = $model->getByMobileNo($model->mobileNo);
            if (!empty($dbModel)) {
                if ($model->token == $dbModel->token) {
                    $statusCode = ApiStatusMessage::SUCCESS;
                    Yii::$app->appLog->writeLog('Verification success');
                } else {
                    $statusCode = ApiStatusMessage::FAILED;
                    Yii::$app->appLog->writeLog('Verification failed');
                }
            } else {
                $statusCode = ApiStatusMessage::RECORD_NOT_EXISTS;
                Yii::$app->appLog->writeLog('Record not exists');
            }
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}
?>