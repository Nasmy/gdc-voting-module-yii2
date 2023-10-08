<?php

namespace app\modules\api\controllers;

class NomineeController extends ApiBaseController
{
    public function actions()
    {
        return array(
            'search' => 'app\modules\api\controllers\actions\nominee\Search',
        );
    }
}
