<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Nominee */

$this->pageTitle = Yii::t('app', 'View Nominee');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nominees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nominee-view view-layout-table">

    <p>
        <?php if (Yii::$app->user->can('Nominee.Update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?php endif ?>

        <?php if (Yii::$app->user->can('Nominee.Delete')): ?>
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
            'description',
            [
                'attribute' => 'image',
                'value' => $model->imageWebPath,
                'format' => ['image', ['width' => '80', 'height' => '80']],
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
