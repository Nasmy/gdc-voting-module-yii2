<?php

/* @var $this yii\web\View */
/* @var $model app\models\Voter */

$this->pageTitle = Yii::t('app', 'Update Voter');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="voter-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
