<?php

namespace app\modules\api\controllers\actions\util;


use Yii;
use yii\base\Action;
use app\components\Aws;
use app\components\Image;
use app\modules\api\components\Message;
use app\models\UploadFile;
use app\modules\api\components\ApiStatusMessage;

class S3Upload extends Action
{
    public function run()
    {
        $params = Yii::$app->request->post();

        $statusCode = ApiStatusMessage::FAILED;
        $statusMsg = null;

        // Take it from model > params > via User
        $signed = false;

        $extraParams = [];

        $model = new UploadFile();
        $image = new Image();
        $model->scenario = UploadFile::SCENARIO_API_CREATE;
        $model->attributes = $params;
        $model->s3Options = null != $model->s3Options ? json_decode($model->s3Options, true) : [];
        $model->options = null != $model->options ? json_decode($model->options, true) : [];

        $fileData = @$_FILES;

        Yii::$app->appLog->writeLog('Request data: ', [$params]);
        Yii::$app->appLog->writeLog('File data: ', [$fileData]);

        if (isset($fileData['file'])) {

            if (UPLOAD_ERR_OK === $fileData['file']['error']) {

                if ($model->validateModel()) {
                    $aws = new Aws();
                    $result = [];

                    // Not image
                    if (!$model->isImage($fileData['file']['type'])) {
                        // Just upload document files as it is
                        $result['main'] = [
                            'awsRes' => $aws->s3UploadObject($model->fileName, $fileData['file']['tmp_name'], $model->s3Options),
                            'fileName' => $model->fileName,
                            'fileUrl' => $aws->s3GetObjectUrl($model->fileName, $signed),
                        ];
                    // Image
                    } else {
                        // Main image upload
                        if (UploadFile::COMPRESS_YES == @$model->options['compress']) {
                            // Compress main image and upload
                            $compressFileName = 'comp-' . $model->fileName;
                            $destPath = Yii::$app->params['tempPath'] . $compressFileName;
                            if ($image->resizeByWidth($fileData['file']['tmp_name'], $destPath)) {
                                $result['main'] = [
                                    'awsRes' => $aws->s3UploadObject($model->fileName, $destPath, $model->s3Options),
                                    'fileName' => $model->fileName,
                                    'fileUrl' => $aws->s3GetObjectUrl($model->fileName, $signed),
                                    'tmpFile' => $destPath
                                ];
                            }
                        } else {
                            // No compression upload as it is
                            $result['main'] = [
                                'awsRes' => $aws->s3UploadObject($model->fileName, $fileData['file']['tmp_name'], $model->s3Options),
                                'fileName' => $model->fileName,
                                'fileUrl' => $aws->s3GetObjectUrl($model->fileName, $signed)
                            ];
                        }

                        // Thumbnail image upload
                        if (UploadFile::THUMBNAIL_YES == @$model->options['thumbnail']) {
                            $thumbFileName = 'thumb_' . $model->fileName;
                            $destPath = Yii::$app->params['tempPath'] . $thumbFileName;
                            if ($image->resizeByWidth($fileData['file']['tmp_name'], $destPath, $model->options['thumbnailWidth'])) {
                                $result['thumb'] = [
                                    'awsRes' => $aws->s3UploadObject($thumbFileName, $destPath, $model->s3Options),
                                    'fileName' => $thumbFileName,
                                    'fileUrl' => $aws->s3GetObjectUrl($thumbFileName, $signed),
                                    'tmpFile' => $destPath
                                ];
                            }
                        }
                    }

                    if ($this->isAllSuccess($result)) {
                        Yii::$app->appLog->writeLog('File upload success');
                        $statusCode = ApiStatusMessage::SUCCESS;
                        $extraParams = Message::s3FileInfo(@$result['main']['fileName'], @$result['main']['fileUrl'], @$result['thumb']['fileName'], @$result['thumb']['fileUrl']);
                    }

                    @unlink(@$result['main']['tmpFile']);
                    @unlink(@$result['thumb']['tmpFile']);
                }
            } else {
                $array = $model->getError($fileData['file']['error']);
                $model->statusCode = $array['code'];
                $model->statusMessage = $array['message'];
            }

        } else {
            $statusCode = ApiStatusMessage::MISSING_MANDATORY_FIELD;
        }

        $statusCode = !empty($model->statusCode) ? $model->statusCode : $statusCode;
        $statusMsg = !empty($model->statusMessage) ? $model->statusMessage : $statusMsg;

        $response = Message::status($statusCode, $statusMsg, $extraParams);
        $this->controller->sendResponse($response);
    }

    /**
     * Check whether all uploads were succeeded
     * @param array $result AWS upload response of each file
     * @return boolean true/false
     */
    private function isAllSuccess($result)
    {
        $allSuc = true;
        foreach ($result as $res) {
            if ('' == @$res['awsRes']['ObjectURL']) {
                $allSuc = false;
            }
        }

        return $allSuc;
    }
}
?>