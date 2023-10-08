<?php

namespace app\modules\api\controllers;

class UtilController extends ApiBaseController
{
    public function actions(){
        return array(
            's3upload' => 'app\modules\api\controllers\actions\util\S3Upload',
            's3file' => 'app\modules\api\controllers\actions\util\S3File',
        );
    }
}