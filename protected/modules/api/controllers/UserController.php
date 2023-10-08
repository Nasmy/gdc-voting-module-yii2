<?php

namespace app\modules\api\controllers;

class UserController extends ApiBaseController
{
    public function actions(){
        return array(
            'index' => 'app\modules\api\controllers\actions\user\Index',

            ///
            'create' => 'app\modules\api\controllers\actions\user\Create',
            'update' => 'app\modules\api\controllers\actions\user\Update',
            'view' => 'app\modules\api\controllers\actions\user\View',
            ///

            'delete' => 'app\modules\api\controllers\actions\user\Delete',

            ///
            'authenticate' => 'app\modules\api\controllers\actions\user\Authenticate',
            'send-verify-token' => 'app\modules\api\controllers\actions\user\SendVerifyToken',
            'verify-token' => 'app\modules\api\controllers\actions\user\VerifyToken',
            'forgot-password' => 'app\modules\api\controllers\actions\user\ForgotPassword',
            'reset-password' => 'app\modules\api\controllers\actions\user\ResetPassword',
            'change-password' => 'app\modules\api\controllers\actions\user\ChangePassword',
            ///

        );
    }
}
