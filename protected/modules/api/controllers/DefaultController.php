<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\api\components\ApiStatusMessage;
use app\modules\api\components\Message;

class DefaultController extends ApiBaseController
{
    public function actionError()
    {
        $statusCode = ApiStatusMessage::FAILED;
        $commonResponse = Message::status($statusCode, Yii::t('app', 'An error occur while processing the request'));
        $this->sendResponse($commonResponse);
    }
}
