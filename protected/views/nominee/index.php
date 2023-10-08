<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NomineeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/*
TODO: Search Form/Grid View (View/Update/Delete) use Pjax correctly
E.g.: Delete submit & referesh the page.
http://www.yiiframework.com/forum/index.php/topic/67405-gridview-delete-action-does-not-send-ajax-request-with-pjax/
*/

$this->pageTitle = Yii::t('app', 'Nominees');
$this->pageTitleDescription = Yii::t('app', 'Listing all nominees');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nominees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>
<div class="nominee-index">

    <?php if (Yii::$app->user->can('Nominee.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create Nominee'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id' => 'dataGridPjax']); ?>
    <?=
    GridView::widget([
        'id' => 'dataGrid',
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width: 2.5%;'],
                'contentOptions' => ['style' => 'width: 2.5%; text-align: center;'],
            ],
            [
                'attribute' => 'name',
                //'value' => function ($model) {
                //    $url = Url::to(['nominee/votes', 'nomineeId' => $model->id]);
                //    return Html::a($model->name, $url, ['data-pjax' => 0]);
                //},
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 20%;'],
                'contentOptions' => ['style' => 'width: 20%;'],
            ],
                        [
                'attribute' => 'categoryName',
                'value' => function ($model) {
                    return $model->categoryName;
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 30%;'],
                'contentOptions' => ['style' => 'width: 30%;'],
            ],
            [
                'attribute' => 'description',
                'headerOptions' => ['style' => 'width: 30%;'],
                'contentOptions' => ['style' => 'width: 30%;'],
            ],
            [
                'attribute' => 'imageName',
                'value' => function ($model) {
                    return $model->imageWebPath;
                },
                'format' => ['image', ['width' => '100px']],
                'filter' => false,
                'headerOptions' => ['style' => 'width: 10%;'],
                'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'headerOptions' => ['style' => 'width: 7.5%; text-align: center'],
                'contentOptions' => ['style' => 'width: 7.5%; text-align: center'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Yii::$app->user->can('Nominee.View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0]) : '';
                    },
                    'update' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Nominee.Update')) {
                            $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit', 'data-pjax' => 0]);
                        }
                        return $return;
                    },
                    'delete' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Nominee.Delete')) {
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
        'layout' => "<div class='table-wrapper nominee-tbl'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
