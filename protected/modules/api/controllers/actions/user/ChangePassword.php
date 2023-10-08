<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;


class ChangePassword extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);
        $user = $this->controller->user;

        $userId = $user->id;
        $model = User::findOne($userId);
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        if (!empty($model)) {
            $model->scenario = User::SCENARIO_API_CHANGE_PASSWORD;
            $model->curOldPassword = $model->password;
            $model->attributes = $params;

            if ('' != @$params['password']) {
                $model->password = $model->encryptPassword(base64_decode($params['password']));
            } else {
                $model->password = '';
            }

            if ('' != @$params['oldPassword']) {
                $model->oldPassword = User::getComparingPassword(base64_decode($model->oldPassword), $model->curOldPassword);
            }

            if ($model->saveModel()) {
                $statusCode = ApiStatusMessage::SUCCESS;
            }
        } else {
            Yii::$app->appLog->writeLog('Record not exists or not allowed');
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}