<?php

use yii\web\View;
use yii\helpers\Html;
use app\models\Voter;
use app\models\Vote;
use yii\helpers\Url;

$formatter = \Yii::$app->formatter;
?>

<div class="baillif-dashboard">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-12">
                <div class="heading"><?= Yii::t('app', 'Dashboard') ?></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div
                    class="sub-heading padding-top-20 padding-bottom-20"><?= Yii::t('app', 'Voting Progress') ?></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">

                <div class="export-txt padding-top-20 padding-bottom-20">
                    <?= Yii::t('app', 'Export Results as') ?>
                    <span>
                        <?php
                        echo Html::a(Yii::t('app', 'PDF'), ['export-report-as-pdf'], [
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                        ]);
                        ?>
                    </span>
                    <span>
                        <?php
                        echo Html::a(Yii::t('app', 'Email'), ['send-voting-results-email'], [
                            'data-toggle' => 'tooltip',
                            'title' => ''
                        ]);
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="row chart-wrapper">
                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-1"></div>
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 voted-doughnut-chart-wrapper">
                    <canvas id="voted-doughnut-chart" width="200" height="200"></canvas>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-5 col-xs-5">
                    <div class="no-of-votes"><?= $doughnutChartParams1['voters'] ?></div>
                    <div class="vote-desc"><?= Yii::t('app', 'Total Voters') ?></div>

                    <div class="no-of-votes"><?= $doughnutChartParams1['votedVoters'] ?></div>
                    <div class="vote-desc"><?= Yii::t('app', 'Voted Already') ?></div>

                    <div class="no-of-votes"><?= $doughnutChartParams1['notVotedVoters'] ?></div>
                    <div class="vote-desc"><?= Yii::t('app', 'Not Voted Yet') ?></div>
                </div>
            </div>
            <div class="row chart-wrapper">
                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-1"></div>
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 days-doughnut-chart-wrapper">
                    <canvas id="days-doughnut-chart" width="200" height="200"></canvas>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-5 col-xs-5">

                    <div class="vote-label"><?= Yii::t('app', 'Voting Started At') ?></div>
                    <div class="vote-date"><?= $formatter->asDate($doughnutChartParams2['votingStartAt'], 'php: d/m/Y'); ?></div>

                    <div class="vote-label"><?= Yii::t('app', 'Voting Ends At') ?></div>
                    <div class="vote-date"><?= $formatter->asDate($doughnutChartParams2['votingEndAt'], 'php: d/m/Y'); ?></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 chart-wrapper">
            <canvas id="days-by-vote-chart" width="550" height="390"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="content-heading padding-top-20 padding-bottom-20"><?= Yii::t('app', 'Winners of the vote'); ?></div>

            <?php
            $i = 1;
            foreach ($categories as $category):

                //Get relavent vote details for the category
				// TODO: we need to keep in separate common place.
				Yii::$app->db->pdo->exec('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
                $vote = new Vote();
                $votes = $vote->find()
                        ->joinWith(['voter'])
                        ->select(['*, COUNT(*) as totalVotes'])
                        ->where(['categoryId' => $category->id])
                        ->andWhere(['Voter.voted' => Voter::VOTED_YES])
                        ->groupBy(['nomineeId'])
                        ->orderBy('totalVotes DESC')
                        ->one();

                $imageWebPath = Yii::$app->view->theme->baseUrl . '/images/unknown-pic.png';
                $nomineeName = Yii::t('app', 'Not Voted');
                $totalVotes = 0;

                if (!empty($votes)) {
                    $imageWebPath = $votes->nominee->imageWebPath;
                    $nomineeName = $votes->nominee->name;
                    $totalVotes = $votes->totalVotes;
                }

                $isRow = $i % 4;
                ?>

                <?= ($isRow == 1) ? '<div class="row padding-bottom-0 padding-top-0">' : ''; ?>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="award-image">
                        <a href="<?= Url::to(['category/detailed-votes', 'id' => $category->id]) ?>">
                            <div class="image">
                                <img src="<?= $imageWebPath ?>"/>
                            </div>
                        </a>
                        <div class="category">
                            <?= $category->name ?>
                        </div>
                        <div class="title">
                            <?= $nomineeName ?>
                        </div>
                        <div class="votes">
                            <?php
                            if ($totalVotes == 0) {
                                echo Yii::t('app', 'Not Voted');
                            } elseif ($totalVotes > 1) {
                                echo $totalVotes . ' ' . Yii::t('app', 'Votes');
                            } else {
                                echo $totalVotes . ' ' . Yii::t('app', 'Vote');
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?= (!$isRow || (count($categories) == $i)) ? '</div>' : ''; ?>

            <?php
                $i++;
            endforeach;
            ?>

        </div>
    </div>
</div>

<?php
$votedPercentage = $doughnutChartParams1['votedPercentage'];
$notVotedPercentage = $doughnutChartParams1['notVotedPercentage'];
$daysRemaining = $doughnutChartParams2['daysRemaining'];
$daysCompleted = $doughnutChartParams2['daysCompleted'];
$days = $barChartParams['days'];
$votes = $barChartParams['votes'];

$strTotalVotes = Yii::t('app', 'Total votes of this week');
$strDays = Yii::t('app', 'Days - {year}', ['year' => date('Y')]);
$strNoVoters = Yii::t('app', 'No# of voters');

$strVotedAlready = Yii::t('app', 'Voted Already');
$strVotingEnds = Yii::t('app', 'Voting ends in');
$strDays = Yii::t('app', 'Days');

$script = <<< JS
    //Global configs for all Charts
    Chart.defaults.global.legend.display = false;
    Chart.defaults.global.tooltips.enabled = false;
    Chart.defaults.global.defaultFontFamily = 'Raleway';
    Chart.defaults.global.defaultFontSize = '14';
    Chart.defaults.global.defaultFontColor = '#121212';
    Chart.defaults.global.backgroundColor = '#d4b469';
    Chart.defaults.global.hoverBackgroundColor = '#cbb37b';

    //Voted already doughnut chart resources
    var ctxVotedDc = $('#voted-doughnut-chart');
    var dataVotedDc = {
        labels: [
            "Yii::t('app', 'Voted')",
            "Yii::t('app', 'Not voted')"
        ],
        datasets: [
            {
                data: [$votedPercentage, $notVotedPercentage],
                backgroundColor: ['#d4b469', '#d6d6d6'],
                hoverBackgroundColor: ['#cbb37b', '#bebebe']
            }
        ]
    };
    var optionsVotedDc = {
        cutoutPercentage:'60',
    };
    var votedDoughnutChart = new Chart(ctxVotedDc, {
        type: 'doughnut',
        data: dataVotedDc,
        options: optionsVotedDc,
    });

    //Voted already and remaining date graph resources
    var ctxDaysDc = $('#days-doughnut-chart');
    var dataDaysDc = {
        labels: [
            "Yii::t('app', 'Days Completed')",
            "Yii::t('app', 'Days Remaining')"
        ],
        datasets: [
            {
                data: [$daysCompleted, $daysRemaining],
                backgroundColor: ['#d4b469', '#d6d6d6'],
                hoverBackgroundColor: ['#cbb37b', '#bebebe']
            }
        ]
    };
    var optionsDaysDc = {
        cutoutPercentage:'60',
    };
    var daysRemainingDoughnutChart = new Chart(ctxDaysDc, {
        type: 'doughnut',
        data: dataDaysDc,
        options: optionsDaysDc
    });

    //Votes by date bar graph resources
    var ctxVotesByDateBc = $('#days-by-vote-chart');
    var dataVotesByDateBc = {
        labels: ["$days"],
        datasets: [
            {
                label: '$strTotalVotes',
                data: [$votes],
                backgroundColor: ['#d4b469', '#d4b469', '#d4b469', '#d4b469', '#d4b469', '#d4b469', '#d4b469'],
                hoverBackgroundColor: ['#cbb37b', '#cbb37b', '#cbb37b', '#cbb37b', '#cbb37b', '#cbb37b', '#cbb37b'],
                borderWidth: 0
            }
        ]
    };
    var optionsVotesByDateBc = {
        hoverBackgroundColor:'#d4b469',
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,

                },
                scaleLabel: {
                    display: true,
                    labelString: '$strDays',
                },

            }],
            yAxes: [{
                gridLines:{
                    color:"rgba(153, 153, 153, 0.7)",
                    zeroLineColor:"rgba(153, 153, 153, 0.7)"
                },
                scaleLabel: {
                    display: true,
                    labelString: '$strNoVoters'
                },
                ticks: {
                    beginAtZero: true
                },
                stacked: true,
                ticks: {
                   min: 0,
                   stepSize: 20,
                }
            }]
        }
    };
    var daysRemainingDoughnutChart = new Chart(ctxVotesByDateBc, {
        type: 'bar',
        data: dataVotesByDateBc,
        options: optionsVotesByDateBc,
    });

    //Chart.js extended before draw function used for add text inside the doughnut
    Chart.pluginService.register({
        beforeDraw:function(chart){
            var width = chart.chart.width,
                height = chart.chart.height,
                ctx = chart.chart.ctx;

            if(ctx.canvas.id == 'voted-doughnut-chart' || ctx.canvas.id == 'days-doughnut-chart'){
                var chartDataArr = chart.data.datasets[0].data;
                var chartValue = chartDataArr[0];

                ctx.restore();
                var fontSize = (height / 125).toFixed(2);
                ctx.textBaseline = "middle";

                if(ctx.canvas.id == 'voted-doughnut-chart'){
                    ctx.font = fontSize + "em Raleway";
                    var text1 = chartValue+' %',
                        text1X = Math.round((width - ctx.measureText(text1).width)/2),
                        text1Y = height/2;
                    ctx.fillText(text1, text1X, text1Y-10);

                    ctx.font = fontSize-0.5 + "em Raleway";
                    var text2 = '$strVotedAlready',
                        text2X = Math.round((width - ctx.measureText(text2).width)/2),
                        text2Y = height/2;
                    ctx.fillText(text2, text2X, text2Y+10);

                } else {
                    ctx.font = fontSize-0.7 + "em Raleway";
                    var text1 = '$strVotingEnds',
                        text1X = Math.round((width - ctx.measureText(text1).width)/2),
                        text1Y = height/2;
                    ctx.fillText(text1, text1X, text1Y-10);

                    ctx.font = fontSize + "em Raleway";
                    var text2 = $daysRemaining+' '+'$strDays',
                        text2X = Math.round((width - ctx.measureText(text2).width)/2),
                        text2Y = height/2;
                    ctx.fillText(text2, text2X, text2Y+10);
                }
                ctx.save();
            }
        }
    });
JS;

$this->registerJs($script, View::POS_END);
