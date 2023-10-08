<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\assets\AppAsset;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Nominee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nominee-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'categoryForm',
        'enableClientValidation' => true,
        //'enableAjaxValidation' => true,
        'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'allowedFileExtensions' => ['jpg', 'gif', 'png'],
                    'showUpload' => false,
                    'initialPreview' => [
                        !empty($model->image) ? Html::img($model->image) : null,
                    ],
                    'overwriteInitial' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
