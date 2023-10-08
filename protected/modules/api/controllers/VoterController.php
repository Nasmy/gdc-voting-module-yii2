<?php

namespace app\modules\api\controllers;

class VoterController extends ApiBaseController
{
    public function actions()
    {
        return [
            'create' => 'app\modules\api\controllers\actions\voter\Create',
            'authenticate' => 'app\modules\api\controllers\actions\voter\Authenticate',
            'ballot' => 'app\modules\api\controllers\actions\voter\Ballot',
            'vote' => 'app\modules\api\controllers\actions\voter\Vote'
        ];
    }
}
