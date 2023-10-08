<?php
/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->pageTitle = Yii::t('app', 'Update Role');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="role-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ])
    ?>

</div>
