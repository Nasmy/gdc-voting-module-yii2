<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
$this->title = 'Login';
?>
<div class="login-header">
    <img class="logo" src="<?php echo Yii::$app->view->theme->baseUrl ?>/images/logo.png" alt="<?php echo Yii::t('app', '{name}', ['name' => Yii::$app->params['productName']]); ?>">
</div>

<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'login-form'],
    'fieldConfig' => [],
]);
?>

    <div class="login-form">

        <fieldset>

            <?php //$form->field($model, 'email') ?>

            <?= $form->field($model, 'username', ['options' => ['class' => 'col-lg-12']])->textInput()->input('text', ['placeholder' => Yii::t('app', 'Your username')]) ?>

            <?= $form->field($model, 'password', ['options' => ['class' => 'col-lg-12']])->passwordInput()->input('password', ['placeholder' => Yii::t('app', 'Your password')]) ?>
            <!--
            <div class="col-lg-8 form-group">
                <div class="txt-small">Forgot your password?</div>
            </div>
            -->
            <div class="col-lg-6 form-group">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'submit-btn form-control', 'style' => '', 'name' => 'login-button']) ?>
                <?php //Html::a(Yii::t('app', 'Forgot Password'), ['user/forgot-password'], ['class' => 'btn btn-info btn-block']) ?>
            </div>

        </fieldset>

    </div>

<?php ActiveForm::end(); ?>