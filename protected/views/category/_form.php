<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
        <div class="category-form">

            <?php
            $form = ActiveForm::begin([
                'id' => 'categoryForm',
                'enableClientValidation' => true,
                //'enableAjaxValidation' => true,
            ]);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'order')->textInput() ?>
                </div>
            </div>
            <?php
            $escape = new JsExpression('function(m) { return m; }');
            echo $form->field($model, 'nomineeListArr')->widget(Select2::classname(), [
                'data' => $model->getNomineeList(),
                'language' => 'en',
                'options' => ['multiple' => true, 'placeholder' => 'Select a nominee ...'],
                'pluginOptions' => [
                    'templateResult' => new JsExpression('format'),
                    'templateSelection' => new JsExpression('format'),
                    'escapeMarkup' => $escape,
                    'allowClear' => true,
                ],
                'showToggleAll' => false,
            ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
<?php
$imageBaseUrl = Url::to(Url::base() . '/' . Yii::$app->params['uploadDir'] . 'nominee' . '/');

$script = <<< JS
function format(state){
    if (!state.id) return state.text;
    var nameArr = state.text.split('_');
    src = '$imageBaseUrl' + nameArr[1];
    return '<img class="flag" src="' + src + '"/>' + nameArr[0];
}
JS;
$this->registerJs($script, View::POS_HEAD);
