<?php

namespace app\modules\register;
use Yii;
/**
 * register module definition class
 */
class Register extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\register\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //Identity class
        Yii::$app->set('user', [
            'class' => 'app\components\WebUser',
            //'identityClass' => 'app\models\AuthProducer',
            'identityClass' => 'app\models\AuthVoter',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['default/login'],
        ]);
        //Error handler
        Yii::$app->errorHandler->errorAction = 'default/error';
    }
}
