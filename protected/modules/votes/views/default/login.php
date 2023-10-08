<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
$this->title = 'Login';
?>
    <div class="login-header">
        <img class="logo" src="<?php echo Yii::$app->view->theme->baseUrl ?>/images/logo.png"
             alt="<?php echo Yii::t('app', '{name}', ['name' => Yii::$app->params['productName']]); ?>">
    </div>
<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'login-form'],
    'action' => ['login'],
    'method' => 'post',
]);
?>
    <div class="login-form">
        <fieldset>
            <?= $form->field($model, 'token', ['options' => ['class' => 'col-lg-12']])->textInput()->input('text', ['placeholder' => "ex: XXXXXX"]) ?>
            <div class="col-lg-8 form-group">

            </div>
            <div class="col-lg-4 form-group">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'submit-btn form-control', 'style' => '', 'name' => 'login-button']) ?>
            </div>
        </fieldset>
    </div>
<?php ActiveForm::end(); ?>