<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\UserCategory;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;


class View extends Action
{
    public function run()
    {
        $response = [];

        $userId = Yii::$app->request->get('id');

        $model = User::findOne($userId);

        $categories = UserCategory::getCategoryArray($userId);

        if (!empty($model)) {
            $response = Message::user($model, $categories);
        } else {
            $response = Message::status(ApiStatusMessage::RECORD_NOT_EXISTS, null);
            Yii::$app->appLog->writeLog('Record not exists.');
        }

        $this->controller->sendResponse($response);
    }
}
?>