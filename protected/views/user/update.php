<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'Update User');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
