<?php

namespace app\modules\api\controllers\actions\user;

use Yii;
use yii\base\Action;
use app\models\Base;
use app\models\User;
use app\models\UserCategory;
use app\components\Mail;
use app\modules\api\components\Message;
use app\modules\api\components\ApiStatusMessage;

class Create extends Action
{
    public function run()
    {
        $params = json_decode(Yii::$app->request->rawBody, true);

        $mail = new Mail();

        $model = new User();
        $model->scenario = User::SCENARIO_API_CREATE;
        $model->attributes = $params;

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $isAllValid = true;
        $isAllSave = true;

        $model->emailConfirmed = User::EMAIL_CONFIRM_NO;
        $model->gender = User::GENDER_UNKNOWN;
        $model->occupationType = User::OCCUPATION_TYPE_UNKNOWN;
        $model->language = !empty($model->language) ? $model->language : Yii::$app->params['defaultLanguage'];
        $model->timeZone = !empty($model->timeZone) ? $model->timeZone : Yii::$app->params['defaultTimeZone'];
        $model->type = User::TYPE_GENERAL;
        $model->status = User::STATUS_INACTIVE;

        $selUserCategoryIds = $params['categories'];
        $selUserCategories = [];

        if (null != $model->password) {
            $model->formPassword = $model->password;
            $model->password = $model->encryptPassword($model->formPassword);
        }

        if ($model->isAnySignupParamExists()) {
            $transaction = Yii::$app->db->beginTransaction();

            $isAllSave = $model->saveModel();
            if ($isAllSave) {

                // Load user selected categories to UserCategory - Start
                if (!empty($selUserCategoryIds)) {

                    foreach($selUserCategoryIds as $id) {
                        $userCategory = new UserCategory();
                        $userCategory->userId = $model->id;
                        $userCategory->categoryId = $id;
                        $selUserCategories[] = $userCategory;
                    }

                    // Validate & Save UserCategory - Start
                    $isAllValid = Base::validateMultipleModels($selUserCategories);
                    if ($isAllValid) {
                        $isAllSave = Base::saveMultipleModels($selUserCategories);
                    }
                    // Validate & Save UserCategory - End
                }
                // Load user selected categories to UserCategory - End
            }

            if ($isAllValid && $isAllSave) {
                $transaction->commit();
                $statusCode = ApiStatusMessage::SUCCESS;
                $mail->language = $model->language;
                $mail->sendSignupEmail($model->email, $model->getFullName());
                Yii::$app->appLog->writeLog('All transactions success. Transaction commit.');
            } else {
                $transaction->rollBack();
                Yii::$app->appLog->writeLog('Some transactions failed. Transaction rollback.');
            }

        } else {
            $statusCode = ApiStatusMessage::MISSING_MANDATORY_FIELD;
            $statusMsg = 'Missing social login id or email/password combination';
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg);
        $this->controller->sendResponse($response);
    }
}
?>