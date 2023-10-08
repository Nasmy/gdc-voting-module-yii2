<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\User;
use app\models\UserCategory;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Update extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $userId = Yii::$app->request->get('id');

        $model = User::findOne($userId);

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $isAllValid = true;
        $isAllSave = true;
        $isAllDelete = true;

        if (!empty($model)) {
            $model->scenario = User::SCENARIO_API_UPDATE;
            $model->attributes = $params;

            $transaction = Yii::$app->db->beginTransaction();

            $isAllSave = $model->saveModel();
            if ($isAllSave) {

                // Load from DB - UserCategory - Start
                $dbUserCategoryIds = UserCategory::getCategoryIds($userId);
                // Load from DB - UserCategory - End

                // Load from POST - UserCategory - Start
                $selUserCategoryIds = $params['categories'];
                // Load from POST - UserCategory - End

                // New user category selection ids
                $newUserCategoryIds = array_diff($selUserCategoryIds, $dbUserCategoryIds);

                // Delete user category selection ids
                $delUserCategoryIds = array_diff($dbUserCategoryIds, $selUserCategoryIds);

                // Add user category ids
                if (!empty($newUserCategoryIds)) {
                    $isAllSave = UserCategory::addCategoryIds($userId, $newUserCategoryIds);
                }

                // Delete user category ids
                if (!empty($delUserCategoryIds)) {
                    $isAllDelete = UserCategory::deleteCategoryIds($userId, $delUserCategoryIds);
                }
            }

            if ($isAllSave && $isAllDelete) {
                $transaction->commit();
                $statusCode = ApiStatusMessage::SUCCESS;
                Yii::$app->appLog->writeLog('All success. Transaction commit.');
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Some transactions failed. Transaction rollback.');
            }

        } else {
            $statusCode = ApiStatusMessage::RECORD_NOT_EXISTS;
            Yii::$app->appLog->writeLog('Record not exists.');
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}
?>