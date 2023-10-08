<?php

namespace app\modules\api\controllers\actions\voter;

use Yii;
use yii\base\Action;
use app\models\VoterAuth;
use app\models\Voter;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Authenticate extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $token = null;
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;
        $data = [];

        $auth = new VoterAuth();
        $auth->attributes = $params;
        $token = $auth->token;

        if ($auth->validateModel()) {
            $model = new Voter();
            $model = $auth->authenticate();

            if ($model) {
                $statusCode = ApiStatusMessage::SUCCESS;
                $data = Message::voter($model);
            } else {
                $statusCode = ApiStatusMessage::INVALID_TOKEN;
                Yii::$app->appLog->writeLog('Invalid token.');
            }
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);

        if (ApiStatusMessage::SUCCESS === $statusCode) {
            $response = Message::authenticationResponse($commonStatus, !empty($model)? $model->token : $token, 'voter', $data);
        } else {
            $response = Message::detailResponse($commonStatus);
        }

        $this->controller->sendResponse($response);
    }
}
?>