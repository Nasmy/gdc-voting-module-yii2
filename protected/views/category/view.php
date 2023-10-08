<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->pageTitle = Yii::t('app', 'View Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="category-view view-layout-table">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?php
    //$nominees = $model->getNominees()->all();
    //print_r($nominees); die();
    ?>

    <?=

    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'order',
            [
                'format' => 'raw',
                'label' => $model->getAttributeLabel('nominees'),
                'value' => $model->getNomineesAsHtml(),
            ],
			[
                'label' => $model->getAttributeLabel('createdAt'),
                'value' => Yii::$app->formatter->asDateTime($model->createdAt),
            ],
			[
                'label' => $model->getAttributeLabel('updatedAt'),
                'value' => Yii::$app->formatter->asDateTime($model->updatedAt),
            ],            
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
