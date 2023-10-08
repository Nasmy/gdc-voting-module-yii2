<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\components\Mail;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;


class ResetPassword extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);
        $mail = new Mail();
        $user = new User();
        $user->scenario = User::SCENARIO_API_RESET_PASSWORD;
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $user->attributes = $params;

        if ($user->validateModel()) {
            $model = $user->getUserByPwResetToken($user->passwordResetToken);
            if (!empty($model)) {
                $model->password = $model->encryptPassword($user->password);
                if ($model->saveModel()) {
                    $statusCode = ApiStatusMessage::SUCCESS;
                    $mail->language = $model->language;
                    $mail->sendPasswordResetEmail($model->email, $model->getFullName());
                }
            } else {
                $statusCode = ApiStatusMessage::RECORD_NOT_EXISTS;
                Yii::$app->appLog->writeLog('No user found for the token.', ['token' => $user->passwordResetToken]);
            }
        }

        $statusCode = !empty($user->statusCode) ? $user->statusCode : $statusCode;
        $statusMsg = !empty($user->statusMessage) ? $user->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}
?>