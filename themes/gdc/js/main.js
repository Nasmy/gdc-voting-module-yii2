//Main functions for DOM ready
$(document).ready(function () {
    setPaddingHeaderCircles();//Set dynamic paddings between header circles
    setContainerMinHeight();//Set main container height
    setLoginTopPosition();//Set the top position for login form

    addOpacity('.header-circle-line-wrapper', 1);//TO DO

    //Load bootstrap dialog - Confirmation
    // $('.confirm-and-exit').click(function () {
    //     loadConfirmExitDialog();
    // });

    //Draw charts for baillif dashboard
    if($('#voted-doughnut-chart').length || $('#days-doughnut-chart').length || $('#days-by-vote-chart').length){
        //drawBaillifCharts();
    }
});

//Handle window resize events
$(window).resize(function () {
    setPaddingHeaderCircles();//Set dynamic paddings between header circles
    setContainerMinHeight();//Set main container height dynamically
    setLoginTopPosition();//Set the top position for login form
});

//Handle window scroll events
$(window).scroll(function () {
    if ($(document).scrollTop() > 50) {
        $('nav').addClass('shrink');
    } else {
        $('nav').removeClass('shrink');
    }
});

//Set the correct margin for header circles
function setPaddingHeaderCircles() {
    //Get width and count
    var lineWidth = $('.header-line-wrapper').width();
    lineWidth = parseInt(lineWidth);
    var circleCount = $('.header-circle-wrapper').find('div.header-circle').length;
    circleCount = parseInt(circleCount);
    //var circleWidth = $('.header-circle').width();
    var circleWidth = 20;
    circleWidth = parseInt(circleWidth);

    //Average line space without circles
    var avgLineWidth = lineWidth - (circleWidth * circleCount);

    //Average margin for each circles
    var avgMargin = avgLineWidth / (circleCount - 1);
    avgMargin = parseInt(avgMargin);

    //Add margin for circles
    $('.header-circle-wrapper').find('a:not(:last-child) > div.header-circle').css({'margin-right': avgMargin + 'px'});
    //$('.header-circle-wrapper').find('a:last-child > div.header-circle').css({'margin-left': avgMargin + 'px'});
}

//Set min height for containers dynamically
function setContainerMinHeight(){
    var navHeight = $('nav').height();
    var footerHeight = $('footer').height();
    var windowHeight = $(window).height();
    var containerHeight = windowHeight-(navHeight+footerHeight);
    containerHeight = containerHeight+60;//Some aditional heights
    $('.main-content-area').css('min-height', containerHeight+'px');
}

//Calculate and set the top position for login form
function setLoginTopPosition(){
    var windowHeight = $(window).height();
    var loginFormHeight = $('.login-form-wrapper').height();
    var topPosition = (windowHeight - loginFormHeight)/2;
    topPosition = topPosition-20;
    $('.login-form-wrapper').css({'position':'relative', 'top':topPosition+'px'});
}

//Set visible opacity for divs
function addOpacity(selector, opacity) {
    $(selector).css('opacity', opacity);
}

//Load dialog model to show the confirmation- Bootstrap new version
/*function loadConfirmExitDialog() {
    BootstrapDialog.show({
        title: 'Confirm & Exit',
        message: 'You have not voted for all the categories, if you submit now you will not be able to change your choices again. Are you sure?',
        buttons: [{
            label: 'Yes, Submit',
            id: 'confirm-vote-submit',
            action: function (dialog) {
                dialog.close();
                loadConfirmedDialog();
            }
        }, {
            label: 'No, Go Back',
            id: 'close-vote-submit',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
}*/

//Load dialog model to show success msg - Bootstrap new version
/*function loadConfirmedDialog() {
    BootstrapDialog.show({
        title: 'Success',
        message: 'Your votes succesfully submitted to the system. Thank you.',
        buttons: [{
            label: 'Exit',
            id: 'close-vote-submit',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
}*/

//Draw charts for baillif section
/*
function drawBaillifCharts(){
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
            'Voted',
            'Not voted'
        ],
        datasets: [
            {
                data: [60, 18],
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
            'Days Completed',
            'Days Remaining'
        ],
        datasets: [
            {
                data: [40, 60],
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
        labels: [
            '01/05',
            '01/06',
            '01/07',
            '01/08',
            '01/09',
            '01/10',
            '01/11'
        ],
        datasets: [
            {
                label:'Total votes of this week',
                data: [45, 60, 200, 123, 70, 50, 66],
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
                    labelString: 'Days - 2017',
                }
            }],
            yAxes: [{
                gridLines:{
                    color:"rgba(153, 153, 153, 0.7)",
                    zeroLineColor:"rgba(153, 153, 153, 0.7)"
                },
                scaleLabel: {
                    display: true,
                    labelString: 'No# of voters'
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
                    var text2 = "Voted already",
                        text2X = Math.round((width - ctx.measureText(text2).width)/2),
                        text2Y = height/2;
                    ctx.fillText(text2, text2X, text2Y+10);

                } else {
                    ctx.font = fontSize-0.5 + "em Raleway";
                    var text1 = 'Voting ends in',
                        text1X = Math.round((width - ctx.measureText(text1).width)/2),
                        text1Y = height/2;
                    ctx.fillText(text1, text1X, text1Y-10);

                    ctx.font = fontSize + "em Raleway";
                    var text2 = chartValue+' days',
                        text2X = Math.round((width - ctx.measureText(text2).width)/2),
                        text2Y = height/2;
                    ctx.fillText(text2, text2X, text2Y+10);
                }
                ctx.save();
            }
        }
    });
}*/