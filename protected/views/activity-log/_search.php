<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ActivityLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'activityAt') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'module') ?>

    <?php // echo $form->field($model, 'controller') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'data') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
