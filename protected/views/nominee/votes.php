<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NomineeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Nominee Votes');
$this->pageTitleDescription = Yii::t('app', 'Listing all nominee votes');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nominees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Nominee Votes');
//print_r($dataProvider);
?>
<div class="baillif baillif-voters">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="heading"><?= Yii::t('app', 'Voters for the nominee') ?></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="sub-heading padding-top-20 padding-bottom-20"><?= $dataProvider->count; ?> <?= Yii::t('app', 'Votes') ?></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            <!--div class="export-txt padding-top-20 padding-bottom-20"><?= Yii::t('app', 'Export Results as') ?> <span>PDF</span>
                <span><?= Yii::t('app', 'Email') ?></span></span></div-->
            <?php
            /*
            $gridColumns = [
                ['class' => 'kartik\grid\SerialColumn'],
                'id',
            ];
            */
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'name',
                        'label' => Yii::t('app', 'Name'),
                    ],
                    [
                        'attribute' => 'email',
                        'label' => Yii::t('app', 'Email'),
                    ],
                    [
                        'attribute' => 'phoneNo',
                        'label' => Yii::t('app', 'Phone No'),
                    ],
                    [
                        'attribute' => 'votedDate',
                        'label' => Yii::t('app', 'Voted Date'),
                    ],
                    [
                        'attribute' => 'votedTime',
                        'label' => Yii::t('app', 'Voted Time'),
                    ],
                ],
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => Yii::t('app', 'Export as'),
                    'class' =>  'btn btn-info'
                ],
                'columnBatchToggleSettings' => [
                    'show' => false,
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_EXCEL_X => [
                        'label' => 'Excel',
                        ],
                ],
                'target' => ExportMenu::TARGET_BLANK,
                'showConfirmAlert' => false,
                'filename' => Yii::t('app', 'voters_for_the_nominee').'_'.date('Y-m-d'),
            ]);
            ?>
        </div>
    </div>

    <?php
    //print_r($dataProvider); die();
    ?>
        <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php Pjax::begin(['id' => 'dataGridPjax']); ?>
		<?php //print_r(DateTimeZone::listIdentifiers()); ?>
        <?=
        GridView::widget([
            'id' => 'dataGrid',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'name',
                    'label' => Yii::t('app', 'Name'),
                ],
                [
                    'attribute' => 'email',
                    'label' => Yii::t('app', 'Email'),
                ],
                [
                    'attribute' => 'phoneNo',
                    'label' => Yii::t('app', 'Phone No'),
                ],
                [
                    'attribute' => 'votedDate',
                    'label' => Yii::t('app', 'Voted Date'),
                ],
                [
                    'attribute' => 'votedTime',
                    'label' => Yii::t('app', 'Voted Time'),					
                ],
            ],
            'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
        ]);
        ?>
        <?php Pjax::end(); ?>

</div>
