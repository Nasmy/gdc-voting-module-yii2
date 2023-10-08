<?php

namespace app\modules\api\components;

use yii\base\Component;

/**
 * Message class
 * This prepares JSON reply messages.
 */
class Message extends Component
{
    /**
     * Status response message
     * @param string $code status code
     * @param string $message status message
     * @param array $extraParams extra parameters to be sent along with status response
     * @return array
     */
    public static function status($code, $message = null, $extraParams = [])
    {
        $array = [
            'code' => $code,
            'message' => $message
        ];

        if (!empty($extraParams)) {
            $array['extraParams'] = $extraParams;
        }

        return $array;
    }

    /**
     * Detail response message
     * @param array $commonStatusMsg common status response message
     * @param string $objKey object names
     * @param array $objData object data
     * @return array
     */
    public static function detailResponse($commonStatusMsg, $objKey = null, $objData = null)
    {
        $array = [];

        if (is_array($objKey)) {
            $array['status'] = $commonStatusMsg;
            foreach ($objKey as $key => $value) {
                $array[$value] = $objData[$key];
            }
        } elseif (!empty($objKey)) {
            $array = [
                'status' => $commonStatusMsg,
                $objKey => $objData
            ];
        } else {
            $array = [
                'status' => $commonStatusMsg
            ];
        }

        return $array;
    }

    /**
     * Authentication response message
     * @param array $commonStatusMsg common status response message
     * @param string $token user access token
     * @param string $type authentication type (user|voter)
     * @param array $object object details (user|voter object details)
     * @return array
     */
    public static function authenticationResponse($commonStatusMsg, $token, $type, $object)
    {
        return [
            'status' => $commonStatusMsg,
            'token' => $token,
            $type => $object
        ];
    }

    /**
     * Search result
     * @param integer $total total no of records
     * @param array $data result set
     * @return array
     */
    public static function searchResult($total, $data)
    {
        return [
            'total' => (int) $total,
            'data' => $data
        ];
    }

    /**
     * User response message
     * @param user $model user object
     * @param array $categories categories array
     * @return array
     */
    public static function user($model, $categories = [])
    {
        return [
            'id' => $model->id,
            'username' => $model->username,
            'email' => $model->email,
            'firstName' => $model->firstName,
            'lastName' => $model->lastName,
            'profilePicture' => $model->profilePicture,
            'phoneNo' => $model->phoneNo
        ];
    }

    /**
     * User response message with minimum data set
     * @param user $model user object
     * @return mixed
     */
    public static function userMin($model)
    {
        $msg = [
            'id' => $model->id,
            'username' => $model->username,
            'email' => $model->email,
            'firstName' => $model->firstName,
            'lastName' => $model->lastName,
            'profilePicture' => $model->profilePicture,
            'phoneNo' => $model->phoneNo
        ];

        return $msg;
    }

	/**
     * Voter response message
     * @param voter $model voter object
     * @return array
     */
    public static function voter($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'phoneNo' => $model->phoneNo,
            'voted' => $model->voted,
            'token' => $model->token,
            'step' => $model->step,
            'roleName' => $model->roleName
        ];
    }

    /**
     * Category response message
     * @param category $model category object
     * @return array
     */
    public static function category($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name
        ];
    }

    /**
     * Nominee response message
     * @param nominee $model nominee object
     * @param array $categoryIds category ids array
     * @return array
     */
    public static function nominee($model, $categoryIds = [])
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'imageUrl' => $model->imageWebPath,
            'categoryIds' => $categoryIds
        ];
    }

    /**
     * Verification response message
     * @param verification $model verification object
     * @return array
     */
    public static function verification($model)
    {
        return [
            'id' => $model->id,
            'mobileNo' => $model->mobileNo,
            'token' => $model->token,
        ];
    }

    /**
     * File response message
     * @param File $file file object
     * @param string $url file URL
     * @return array
     */
    public static function file($file, $url)
    {
        return [
            'id' => $file->id,
            'fileName' => $file->fileName,
            'comment' => $file->comment,
            'type' => $file->type,
            'fileUrl' => $url
        ];
    }

    /**
     * S3 File
     * @param string $fileName name of the file
     * @param string $url file URL
     * @param string $thumbnailName thumbnail name
     * @param string $thumbnailUrl thumbnail URL
     * @return array
     */
    public static function s3FileInfo($fileName, $url, $thumbnailName = '', $thumbnailUrl = '')
    {
        return [
            'fileName' => $fileName,
            'url' => $url,
            'thumbnailName' => $thumbnailName,
            'thumbnailUrl' => $thumbnailUrl
        ];
    }
}
