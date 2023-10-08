<?php

namespace app\modules\api\controllers\actions\util;

use Yii;
use yii\base\Action;
use app\components\Aws;
use app\modules\api\components\Message;
use app\models\UploadFile;
use app\modules\api\components\ApiStatusMessage;

class S3File extends Action
{

    public function run()
    {
        $params = Yii::$app->request->get();
        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        $model = new UploadFile();
        $model->scenario = UploadFile::SCENARIO_API_FILE_URL;
        $model->attributes = $params;

        if ($model->validateModel()) {
            $aws = new Aws();
            $url = $aws->s3GetObjectUrl($model->fileName, $model->signed);
            if ('' != $url) {
                Yii::$app->appLog->writeLog('S3 file URL retrieval success.');
            }
            $response = Message::s3FileInfo($model->fileName, $url);
        } else {
            $response = Message::status($model->statusCode, $model->statusMessage);
        }

        $this->controller->sendResponse($response);
    }
}
