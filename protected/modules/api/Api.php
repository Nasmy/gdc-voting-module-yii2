<?php

namespace app\modules\api;

use Yii;

class Api extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'api/default/error';
    }

}