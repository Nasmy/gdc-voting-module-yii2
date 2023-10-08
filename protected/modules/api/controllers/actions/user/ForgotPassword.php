<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\components\Mail;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;
use yii\helpers\Html;


class ForgotPassword extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);
        $mail = new Mail();
        $user = new User();
        $user->scenario = User::SCENARIO_API_FORGOT_PASSWORD;
        $user->attributes = $params;
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        if ($user->validateModel()) {
            $model = $user->getUserByEmail($user->email);
            if (!empty($model)) {
                $token = uniqid() . $model->id;
                $model->passwordResetToken = $token;
                if ($model->saveModel()) {
                    $url = Yii::$app->params['passwordRestUrl'] . '?q=' . $token;
                    $mail->language = $model->language;
                    $link = Html::a(Yii::t('mail', 'Reset password', [], $model->language), $url);
                    if ($mail->sendForgotPasswordEmail($user->email, $link)) {
                        $statusCode = ApiStatusMessage::SUCCESS;
                    }
                }
            } else {
                $statusCode = ApiStatusMessage::RECORD_NOT_EXISTS;
                Yii::$app->appLog->writeLog('No user found for the email.', ['email' => $user->email]);
            }
        }

        $statusCode = !empty($user->statusCode) ? $user->statusCode : $statusCode;
        $statusMsg = !empty($user->statusMessage) ? $user->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}
?>