<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//use kartik\select2\Select2;
use app\models\User;
use app\models\Role;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'userForm',
        'enableClientValidation' => true,
    ]);
    ?>

    <div class="row">
        <div class="col-md-6">
            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'username');
            } else {
                echo $form->field($model, 'username')->textInput(['readonly' => true]);
            }
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'formPassword')->passwordInput(['autocomplete' => 'off', 'class' => 'form-control password']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'confPassword')->passwordInput(['autocomplete' => 'off', 'class' => 'form-control password']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'firstName') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lastName') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'roleName')->dropDownList(ArrayHelper::map(Role::find()->where('name != :name', ['name' => Role::SUPER_ADMIN])->all(), 'name', 'name'), ['prompt' => Yii::t('app', '- Role -')]); ?>
        </div>
        <!--
        <div class="col-md-3">
            <?php
            /*
            $form->field($model, 'timeZone')->widget(Select2::classname(), [
                'data' => Yii::$app->util->getTimeZoneList(),
                'language' => 'en',
                'options' => ['placeholder' => Yii::t('app', '- TimeZone -')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            */
            ?>
        </div>
        -->
        <div class="col-md-6">
            <?php //$form->field($model, 'status')->dropDownList($model->getStatuses()); ?>
            <?= $form->field($model, 'status')->dropDownList([User::STATUS_INACTIVE => Yii::t('app', 'Inactive'), User::STATUS_ACTIVE => Yii::t('app', 'Active')], ['prompt' => Yii::t('app', '- Status -')]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
