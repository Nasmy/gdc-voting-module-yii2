<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\RoleSearch */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(
    '$("document").ready(function() {
        $("#searchFormPjax").on("pjax:end", function() {
            $.pjax.reload({container:"#dataGridPjax"});  // Reload dataGridPjax
        });
    });'
);
?>

<div class="role-search">

    <?php Pjax::begin(['id' => 'searchFormPjax']); ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'searchForm',
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true],
    ]);
    ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'description')->textInput(['placeholder' => $model->getAttributeLabel('description')])->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
