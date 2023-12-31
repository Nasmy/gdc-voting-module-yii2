<?php

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->pageTitle = Yii::t('app', 'Update Category');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>