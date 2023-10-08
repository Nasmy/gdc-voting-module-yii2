<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Permissions');
$this->pageTitleDescription = Yii::t('app', 'Listing all permissions');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>

<div class="permission-index">

    <?php if (Yii::$app->user->can('Permission.Create')): ?>
        <p> <?= Html::a(Yii::t('app', 'Create Permission'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif; ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id' => 'dataGridPjax']); ?>
    <?=
    GridView::widget([
        'id' => 'dataGrid',
        'dataProvider' => $dataProvider,
        //'tableOptions' => ['class'=>'table table-striped'],
        'columns' => [
            'name',
            'description:ntext',
            'category',
            'subCategory',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'headerOptions' => ['style' => 'text-align: right'],
                'contentOptions' => ['style' => 'text-align: right'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Yii::$app->user->can('Permission.View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0]) : '';
                    },
                    'update' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Permission.Update')) {
                            $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit', 'data-pjax' => 0]);
                        }
                        return $return;
                    },
                    'delete' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Permission.Delete')) {
                            $return = Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'class' => 'delete',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                        return $return;
                    },
                ],
            ],
        ],
        'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
