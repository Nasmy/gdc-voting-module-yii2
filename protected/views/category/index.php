<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Categories');
$this->pageTitleDescription = Yii::t('app', 'Listing all categories');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>
<div class="category-index">

    <?php if (Yii::$app->user->can('Category.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',                
                'headerOptions' => ['style' => 'width: 2.5%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 2.5%; text-align: center;'],
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'width: 85%;'],
                'contentOptions' => ['style' => 'width: 85%;'],
            ],
            [
                'attribute' => 'order',
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'headerOptions' => ['style' => 'width: 7.5%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 7.5%; text-align: center;'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Yii::$app->user->can('Category.View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url) : '';
                    },
                    'update' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Category.Update')) {
                            $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit']);
                        }
                        return $return;
                    },
                    'delete' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Category.Delete')) {
                            $return = Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'class' => 'delete',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ]
                            ]);
                        }
                        return $return;
                    },
                ],
            ],
        ],
        'layout' => "<div class='table-wrapper category-tbl'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>