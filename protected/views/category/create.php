<?php

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->pageTitle = Yii::t('app', 'Create Category');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="category-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>