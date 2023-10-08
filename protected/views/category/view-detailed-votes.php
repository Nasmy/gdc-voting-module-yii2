<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Voter;
use yii\helpers\Url;
?>
<div class="baillif baillif-award-winners">
    <div class="row">

        <div class="col-lg-12">
            <!--<div class="heading padding-bottom-20">Cinema</div>-->
            <div class="heading padding-bottom-20"><?= $categoryRow->name ?></div>
        </div>

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="sub-heading">
                &nbsp;
                <?php //$categoryRow->name ?><?php //Yii::t('app', 'Award Nominees') ?>
            </div>
        </div>

        <?php

        $form = ActiveForm::begin([
            'id' => 'searchForm',
            'action' => ['detailed-votes'],
            'method' => 'post',
        ]);
        ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 select-box">
            <?php
            echo Html::activeDropDownList($categoryRow, 'id', $categoryRow->getCategoryList(),[
                'class' => 'form-control',
                'onchange'=>'this.form.submit()'
            ])
            ?>
        </div>
        <?php
        ActiveForm::end();

        ?>

        <div class="col-lg-12">
            <div class="row-for-wrap-grid">

                <?php

                Pjax::begin(['id' => 'searchPjax']);
				Yii::$app->db->pdo->exec('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
                $nominees = $categoryRow
                    ->getNominees()
                    ->joinWith(['nomineeVotes'])
                    ->all();

                //TO DO - better way to orderby total votes
                $nomineeArr = [];
                foreach ($nominees as $nominee):
                    $nomineeVotes = $nominee
                        ->getNomineeVotes()
                        ->joinWith(['voter'])
                        ->select(['*, COUNT(*) as totalVotes'])
                        ->where(['categoryId' => $categoryRow->id])
                        ->andWhere(['Voter.voted' => Voter::VOTED_YES])
                        ->one();
                    $nomineeArr[] = [
                        'totalVotes' => $nomineeVotes->totalVotes,
                        'nominee' => $nominee
                    ];
                endforeach;

                usort($nomineeArr, function ($a, $b){
                    if ($a['totalVotes'] == $b['totalVotes']) {
                        return 0;
                    }
                    return ($a['totalVotes'] > $b['totalVotes']) ? -1 : 1;
                });

                $i = 1;
                foreach ($nomineeArr as $row):
                    ?>
                    <div class="col-lg-15 col-md-15 col-sm-4 col-xs-6">

                        <div class="award-image <?= ($i == 1 && $row['totalVotes']) ? 'selected-award-image' : ''?>">
                            <a href="<?= Url::to([
                                'nominee/votes',
                                'nomineeId' => $row['nominee']->id,
                                'categoryId' => $categoryRow->id
                            ]) ?>">
                                <div class="image">
                                    <img src="<?= $row['nominee']->imageWebPath ?>"/>
                                </div>
                            </a>
                            <div class="title">
                                <?= $row['nominee']->name ?>
                            </div>
                            <div class="votes">
                                <?php
                                if ($row['totalVotes'] == 0) {
                                    echo Yii::t('app', 'Not Voted');
                                } elseif ($row['totalVotes'] > 1) {
                                    echo $row['totalVotes'] . ' ' . Yii::t('app', 'Votes');
                                } else {
                                    echo $row['totalVotes'] . ' ' . Yii::t('app', 'Vote');
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                    <?php
                    $i++;
                endforeach;
                Pjax::end();
                ?>

            </div>
        </div>
    </div>
</div>