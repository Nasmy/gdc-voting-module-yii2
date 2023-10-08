<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Role;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
Pjax::begin([
    'id' => 'dataGridPjax',
    'timeout' => false,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'POST'],
]);
?>
<?=
GridView::widget([
    'id' => 'dataGrid',
    'dataProvider' => $dataProvider,
    'columns' => [
        'username',
        'email:email',
        'firstName',
        'lastName',
        'timeZone',
        'roleName',
        [
            'attribute' => 'status',
            'filter' => $model->statuses,
            'value' => function ($model) {
                return !empty($model->status) ? Yii::t('app', 'Active') : Yii::t('app', 'Inactive');
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Actions'),
            'headerOptions' => ['style' => 'text-align: right'],
            'contentOptions' => ['style' => 'text-align: right'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url) {
                    return Yii::$app->user->can('User.View') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0]) : '';
                },
                'update' => function ($url, $model, $key) {
                    $return = '';
                    if (Yii::$app->user->can('User.Update')) {
                        $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit', 'data-pjax' => 0]);
                        if ($model->roleName == Role::SUPER_ADMIN) {
                            $return = '';
                        } else if ($model->email == Yii::$app->user->identity->email) {
                            $return = '';
                        }
                    }
                    return $return;
                },
                'delete' => function ($url, $model, $key) {
                    $return = '';
                    if (Yii::$app->user->can('User.Delete')) {
                        $return = Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'delete',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                        if ($model->roleName == Role::SUPER_ADMIN) {
                            $return = '';
                        } else if ($model->email == Yii::$app->user->identity->email) {
                            $return = '';
                        }
                    }
                    return $return;
                },
            ],
        ],
    ],
    'layout' => "<div class='table-wrapper'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
]);
?>
<?php Pjax::end(); ?>