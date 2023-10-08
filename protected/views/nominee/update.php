<?php

/* @var $this yii\web\View */
/* @var $model app\models\Nominee */

$this->pageTitle = Yii::t('app', 'Update Nominee');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nominees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="nominee-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
