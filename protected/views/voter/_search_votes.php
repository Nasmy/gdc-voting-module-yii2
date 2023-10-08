<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Voter;

/* @var $this yii\web\View */
/* @var $model app\models\VoterSearch */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(
        '$("document").ready(function() {
        $("#searchFormPjax").on("pjax:end", function() {
            $.pjax.reload({container:"#dataGridPjax"});  // Reload dataGridPjax
        });
    });'
);
?>

<div class="voter-votes-search col-md-9">

    <?php Pjax::begin(['id' => 'searchFormPjax']); ?>

    <?php
    $form = ActiveForm::begin([
                'id' => 'searchForm',
                'action' => ['votes'],
                'method' => 'get',
                'options' => ['data-pjax' => true],
    ]);
    ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['placeholder' => Yii::t('app', 'First name or Last name')])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'voted')->dropDownList([Voter::VOTED_NO => Yii::t('app', 'No'), Voter::VOTED_YES => Yii::t('app', 'Yes')], ['prompt' => Yii::t('app', '- Voted -')])->label(false); ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end(); ?>

</div>
