<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VoterSearch */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->pageTitle = Yii::t('app', 'Voters Votes');
$this->pageTitleDescription = Yii::t('app', 'Listing all voters votes');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Voters Votes');
//print_r($dataProvider);
?>
<div class="baillif baillif-voters baillif-voters-all">
    <div class="row">
        <div class="col-lg-12">
            <div class="heading padding-bottom-20"><?= Yii::t('app', 'Voters') ?></div>
        </div>

        <div class="row">

            <?php echo $this->render('_search_votes', ['model' => $searchModel]); ?>

            <div class="col-md-3 text-right export-menu-wrapper">

                <?php
                //TODO : Temp solution for dynamic columns
                //Create default columns and arrange to grid with custom column params
                $columnsArray = [
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
                        'attribute' => 'voted',
                        'label' => Yii::t('app', 'Voted'),
                    ],
                    [
                        'attribute' => 'votedAt',
                        'label' => Yii::t('app', 'Voted Date and Time'),
                    ],
                ];

                $category = new \app\models\Category();
                $categories = $category::find()->orderBy('order')->all();

                //Get categories and arrange to grid columns with custom column params
                $categoryArray = [];
                foreach ($categories as $category){
                    $categoryArray[] = [
                        'attribute' => $category->name,
                        'label' => Yii::t('app', $category->name),
                    ];
                }

                //Merge both array to pass to the grid columns
                $mergedColumns = array_merge($columnsArray, $categoryArray);

                ?>

                <?php
                /*$gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'id',
                ];
                */
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $mergedColumns,
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => Yii::t('app', 'Export as'),
                        'class' => 'btn btn-info'
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_EXCEL_X => [
                            'label' => 'Excel',
                        ],
                        /*ExportMenu::FORMAT_PDF => [
                            'styleOptions' => [
                                'border' => '1px solid #000000'
                            ],
                        ]*/
                    ],
                    'columnBatchToggleSettings' => [
                        'show' => false,
                    ],
                    'target' => ExportMenu::TARGET_BLANK,
                    'showConfirmAlert' => false,
                    'filename' => Yii::t('app', 'voters') . '_' . date('Y-m-d'),
                ]);
                ?>
            </div>
        </div>

        <?php Pjax::begin(['id' => 'dataGridPjax']); ?>
        <?php
        echo GridView::widget([
            'id' => 'dataGrid',
            'dataProvider' => $dataProvider,
            'columns'=> $mergedColumns,
            //'filterModel' => $searchModel,
            'layout' => "<div class='table-wrapper votes-tbl'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
            'summary' => "Affichage de {$beginCount}-{$endCount} sur {$totalResultCount} éléments.",//TODO - Change french text
        ]);
        ?>

        <?php
        echo LinkPager::widget([
            'pagination' => $pagination,
        ]);
        ?>

        <?php Pjax::end(); ?>
    </div>
</div>
