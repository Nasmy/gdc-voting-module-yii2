<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\UserCategory;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Authenticate extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $token = null;
        $userData = [];

        $user = new User();
        $user->scenario = User::SCENARIO_API_AUTH;
        $user->attributes = $params;

        if ($user->validateModel()) {
            // Validate user according to login type
            $model = $user->authUser();
            if ($model) {
                $token = $model->id . '-' . uniqid();
                $model->userToken = $token;
                $model->lastAccess = Yii::$app->util->getUtcDateTime();
                if ($model->saveModel()) {
                    $statusCode = ApiStatusMessage::SUCCESS;
                    $categories = UserCategory::getCategoryIds($model->id);
                    $userData = Message::user($model, $categories);
                }
            } else {
                Yii::$app->appLog->writeLog('Invalid email or password');
            }
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);
        $response = Message::authenticationResponse($commonStatus, $token, $userData);

        $this->controller->sendResponse($response);
    }
}
?>