<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php Pjax::begin(['id' => 'searchPjax']); ?>
    
    <?php
    $form = ActiveForm::begin([
        'id' => 'searchForm',
        'action' => ['index'],
        'method' => 'get',
    ]);
    ?>   

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    
    <?php 
    /*
    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'order') ?>

    <?= $form->field($model, 'createdAt') ?>

    <?= $form->field($model, 'updatedAt') ?>

    <?= $form->field($model, 'createdById') ?>

    <?= $form->field($model, 'updatedById') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
    */
    ?>

    <?php ActiveForm::end(); ?>
    
    <?php Pjax::end(); ?>
    
</div>