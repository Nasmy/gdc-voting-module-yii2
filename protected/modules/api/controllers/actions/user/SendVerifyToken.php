<?php

namespace app\modules\api\controllers\actions\user;


use Yii;
use yii\base\Action;
use app\models\User;
use app\models\Verification;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class SendVerifyToken extends Action
{
    public function run()
    {
        $user = new User();
        $params = json_decode(Yii::$app->request->rawBody, true);
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        //$user = $user->getUserByMobileNo(@$params['mobileNo']);

        //if (empty($user)) {
            $dbModel = new Verification();
            $model = $dbModel->getByMobileNo(@$params['mobileNo']);

            if (empty($model)) {
                $model = new Verification();
                $model->scenario = Verification::SCENARIO_API_CREATE;
                $model->mobileNo = @$params['mobileNo'];
            } else {
                $model->scenario = Verification::SCENARIO_API_UPDATE;
            }


            if ($model->sendTokenViaSms()) {
                $statusCode = ApiStatusMessage::SUCCESS;
            }
        //} else {
        //    $statusCode = ApiStatusMessage::MOBILE_NO_EXISTS;
        //}

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $commonStatus = Message::status($statusCode, $statusMsg);

        $objData = Message::verification($model);

        $response = Message::detailResponse($commonStatus, 'verification', $objData);
        $this->controller->sendResponse($response);
    }
}