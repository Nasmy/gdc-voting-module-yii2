<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RolePermission */

$this->title = $model->roleName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Role Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-permission-view view-layout-table">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'roleName' => $model->roleName, 'permissionName' => $model->permissionName], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'roleName' => $model->roleName, 'permissionName' => $model->permissionName], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'roleName',
            'permissionName',
            'createdAt',
            'updatedAt',
            'createdById',
            'updatedById',
        ],
    ]) ?>

</div>
