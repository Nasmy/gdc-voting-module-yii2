<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Voter */

$this->pageTitle = Yii::t('app', 'View Voter');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="voter-view view-layout-table">

    <p>
        <?php if (Yii::$app->user->can('Voter.Update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?php endif ?>

        <?php if (Yii::$app->user->can('Voter.Delete')): ?>
            <?=
            Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-info',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        <?php endif ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'email:email',
            'phoneNo',
            [
                'attribute' => 'voted',
                'value' => !empty($model->voted) ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
            ],
            'votedAt',
            //'token',
            [
                'attribute' => 'tokenSent',
                'value' => !empty($model->tokenSent) ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
            ],
            'tokenSentAt',
            'step',
            'device',
            'platform',
            'platformVersion',
            'browser',
            'browserVersion',
            'roleName',
            [
                'attribute' => 'status',
                'value' => !empty($model->status) ? Yii::t('app', 'Active') : Yii::t('app', 'Inactive'),
            ],
            'createdAt',
            'updatedAt',
            [
                'label' => $model->getAttributeLabel('createdById'),
                'value' => User::getFullNameById($model->createdById),
            ],
            [
                'label' => $model->getAttributeLabel('updatedById'),
                'value' => User::getFullNameById($model->updatedById),
            ],
        ],
    ])
    ?>

</div>
