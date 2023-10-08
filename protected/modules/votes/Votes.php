<?php

namespace app\modules\votes;
use Yii;
/**
 * voter module definition class
 */
class Votes extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\votes\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //Identity class
        Yii::$app->set('user', [
            'class' => 'app\components\WebUser',
            'identityClass' => 'app\models\AuthVoter',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['default/login'],
        ]);
        //Error handler
        Yii::$app->errorHandler->errorAction = 'default/error';
    }
}
