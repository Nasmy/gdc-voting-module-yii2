<?php

namespace app\modules\api\controllers;

class CategoryController extends ApiBaseController
{
    public function actions()
    {
        return array(
            'search' => 'app\modules\api\controllers\actions\category\Search',
        );
    }

}
