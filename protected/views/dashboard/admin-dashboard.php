<?php
use yii\web\View;
use yii\helpers\Html;
?>

<div class="baillif baillif-dashboard">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-12">
                <div class="heading"><?= Yii::t('app', 'Dashboard') ?></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="sub-heading padding-top-20 padding-bottom-20"><?= Yii::t('app', 'Voting Progress') ?></div>
            </div>
            <!--
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <div class="export-txt padding-top-20 padding-bottom-20">Export Results as <span>PDF</span> <span>Email</span></span></div>
            </div>
            -->
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
                    <canvas id="days-doughnut-chart" width="150" height="150"></canvas>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-5 col-xs-5">

                    <div class="vote-label"><?= Yii::t('app', 'Desktop') ?></div>
                    <div class="vote-date"><?= $doughnutChartParams2['devideDesktopCount'] ?></div>

                    <div class="vote-label"><?= Yii::t('app', 'Mobile') ?></div>
                    <div class="vote-date"><?= $doughnutChartParams2['devideMobileCount'] ?></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 chart-wrapper">
            <canvas id="days-by-vote-chart" width="550" height="390"></canvas>
        </div>
    </div>
    <!--
    <div class="row">
        <div class="col-lg-12">
            <div class="content-heading padding-top-20 padding-bottom-20"><?= Yii::t('app', 'Notifications') ?></div>
            <ul class="dashboard-notifications">
                <li><div class="circle"></div>Second round of reminding progress started</li>
                <li><div class="circle"></div>New voter registered</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
                <li><div class="circle"></div>Proin gravida dolor sit amet lacus accumsan et viverra justo commodo</li>
            </ul>
        </div>
    </div>
    -->
</div>

<?php
$votedPercentage = $doughnutChartParams1['votedPercentage'];
$notVotedPercentage = $doughnutChartParams1['notVotedPercentage'];
$devideDesktopCount = $doughnutChartParams2['devideDesktopCount'];
$devideMobileCount = $doughnutChartParams2['devideMobileCount'];
$days = $barChartParams['days'];
$votes = $barChartParams['votes'];

$strVotedAlready = Yii::t('app', 'Voted Already');
$strTotalVotes = Yii::t('app', 'Total votes of this week');
$strDays = Yii::t('app', 'Days - {year}', ['year' => date('Y')]);
$strNoVoters = Yii::t('app', 'No# of voters');

$labelVoted = Yii::t('app', 'Voted');
$labelPlatform = Yii::t('app', 'Platform');

//print_r($days); die();

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
            "Yii::t('app', 'Desktop')",
            "Yii::t('app', 'Mobile')"
        ],
        datasets: [
            {
                data: [$devideDesktopCount, $devideMobileCount],
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
                }
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
                    beginAtZero: true,
                    min: 0,
                    stepSize: 20,
                },
                stacked: true,
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
                    ctx.font = fontSize-0.5 + "em Raleway";
                    var text1 = '$labelVoted',
                        text1X = Math.round((width - ctx.measureText(text1).width)/2),
                        text1Y = height/2;
                    ctx.fillText(text1, text1X, text1Y-10);

                    ctx.font = fontSize-0.3 + "em Raleway";
                    var text2 = '$labelPlatform',
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
?>


