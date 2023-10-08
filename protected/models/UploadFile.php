<?php

namespace app\models;

use app\modules\api\components\ApiStatusMessage;
use app\models\BaseForm;

class UploadFile extends BaseForm
{
    // Compress file
    const COMPRESS_NO = 0;
    const COMPRESS_YES = 1;

    // Thumbnail
    const THUMBNAIL_NO = 0;
    const THUMBNAIL_YES = 1;

    const SCENARIO_API_CREATE = 'apiCreate';
    const SCENARIO_API_FILE_URL = 'apiFileUrl';

    public $validImgFileTypes = ['image/jpeg', 'image/gif', 'image/png'];
    public $file;
    public $fileName;
    public $fileType;
    public $s3Options;
    public $options;
    public $signed;

    public function rules()
    {
        return [
            // Upload
            [['fileName'], 'required', 'message' => ApiStatusMessage::MISSING_MANDATORY_FIELD, 'on' => [self::SCENARIO_API_CREATE, self::SCENARIO_API_FILE_URL]],
            [['s3Options', 'signed', 'options', 'fileType'], 'safe'],

            //[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, txt, pdf'],
        ];
    }

    public function isImage($mimeType)
    {
        return in_array($mimeType, $this->validImgFileTypes);
    }

    /**
     * Check whether uploaded file type is correct for thumbnail generation
     *
     * @param string $imageName Image name
     * @param string $imagePath Image path
     * @return mixed $img Created image
     */
    //public function isValidFileForThumbCreate($fileName)
    //{
    //    $name = $fileName;//$_FILES['file']['name'];
    //    $nameParts = explode('.', $name);
    //    $ext = end($nameParts);
    //
    //    if (in_array($ext, $this->validImgFileTypes)) {
    //        return true;
    //    }
    //
    //    return false;
    //}

    public function getError($code)
    {
        $array = [];

        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_SIZE_EXCEED;
                $array['message'] = 'The uploaded file size exceeds';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_SIZE_EXCEED;
                $array['message'] = 'The uploaded file size exceeds';
                break;
            case UPLOAD_ERR_PARTIAL:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_PARTIAL;
                $array['message'] = 'The file was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_NO_FILE;
                $array['message'] = 'No file was uploaded';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_NO_TEMP_FOLDER;
                $array['message'] = 'File upload temporary folder missing';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_WRITE_ERROR;
                $array['message'] = 'Unable to upload file to server';
                break;
            case UPLOAD_ERR_EXTENSION:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_EXTENSION_ERROR;
                $array['message'] = 'File upload extension error';
                break;
            default:
                $array['code'] = ApiStatusMessage::FILE_UPLOAD_UNKNOWN_ERROR;
                $array['message'] = 'File upload unknown error';
                break;
        }

        return $array;
    }
}
