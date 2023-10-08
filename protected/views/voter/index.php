<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VoterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Voters');
$this->pageTitleDescription = Yii::t('app', 'Listing all voters');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>
<div class="voter-index">

    <?php if (Yii::$app->user->can('Voter.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create Voter'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>
<div class="row">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <div class="col-md-3 text-right export-menu-wrapper">
        <?php

        /*
		$gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'name',
            'email',
            'phoneNo',
            'voted',
            'votedAt',
            'status',
        ];

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'fontAwesome' => true,
            'dropdownOptions' => [
                'label' => Yii::t('app', 'Export as'),
                'class' =>  'btn btn-info'
            ],
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_TEXT => false,
            ],
            'target' => ExportMenu::TARGET_BLANK,
            'showConfirmAlert' => false,
            'filename' => Yii::t('app', 'voters').'_'.date('Y-m-d'),

        ]);
		*/
        
        ?>
    </div>
</div>

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
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            //'id',
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'width: 20%;'],
            ],
            [
                'attribute' => 'email',
                'headerOptions' => ['style' => 'width: 15%;'],
            ],
            [
                'attribute' => 'phoneNo',
                'headerOptions' => ['style' => 'width: 10%;'],
            ],
			[
                'attribute' => 'tokenSent',
				'header' => Yii::t('app', 'Invitation'),
				'value' => function ($model) {
                    return !empty($model->tokenSent) ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
                },
				'format' => 'raw',
                'headerOptions' => ['style' => 'width: 5%;'],
				'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'reminder1',
                'value' => function ($model) {
                    return !empty($model->reminder1) ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'reminder2',
                'value' => function ($model) {
                    return !empty($model->reminder2) ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'reminder3',
                'value' => function ($model) {
                    return !empty($model->reminder3) ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'voted',
                'value' => function ($model) {
                    return !empty($model->voted) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return !empty($model->status) ? Yii::t('app', 'Active') : Yii::t('app', 'Inactive');
                },
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'headerOptions' => ['style' => 'width: 5%; text-align: center'],
                'contentOptions' => ['style' => 'text-align: center'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Yii::$app->user->can('Voter.View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0]) : '';
                    },
                    'update' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Voter.Update')) {
                            $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit', 'data-pjax' => 0]);
                        }
                        return $return;
                    },
                    'delete' => function ($url, $model, $key) {
                        $return = '';
                        if (Yii::$app->user->can('Voter.Delete')) {
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
        'layout' => "<div class='table-wrapper voter-tbl'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
<p>&nbsp;</p>
