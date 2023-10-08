<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if($maxOrder <= $this->params['step']){
    $nextUrl = Url::to(['voter/votes-summary']);
} else {
    $nextUrl = Url::to(['category/view-by-step', 'step' => $nextOrder]);
}
?>

    <div class="container main-content-area award-category">

        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="heading"><?= $this->title ?></div>
                <!--div class="sub-heading"><?//= Yii::t('app', 'Award Nominees') ?></div-->
                <div class="content-heading  padding-top-50"><?= Yii::t('app', 'Select Your Choice') ?></div>
                <div class="row">
                    <?php
                    $isChecked = false;
                    foreach ($model->nominees as $nominee):
                        ?>
                        <div class="col-lg-15 col-md-15 col-sm-4 col-xs-6 nominee-image-block">
                            <div class="award-image <?= (($existVote !== null) && $existVote->nomineeId === $nominee->id) ? 'award-image-selected' : '' ?>" data-nominee-id="<?= $nominee->id ?>">
                                <div class="image">
                                    <img src="<?= $nominee->imageWebPath ?>"/>
                                    <?php if(($existVote !== null) && $existVote->nomineeId === $nominee->id): ?>
                                        <div class="overlay-selected"><div class="done-image"></div></div>
                                    <?php $isChecked = true; endif; ?>
                                </div>

                                <div class="title">
                                    <?= $nominee->name ?>
                                </div>
                                <div class="description">
                                    <?= $nominee->description ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <div class="row prev-next-skip-btns">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left">
                        <?php
                        if($prevOrder){
                            echo Html::a('<div class="button-style"><span class="style-2 back">'.Yii::t('app', 'Back').'</span> </div>', [
                                'category/view-by-step',
                                'step' => $prevOrder,
                            ]);
                        } else {
                            echo Html::a('<div class="button-style"><span class="style-2 back">'.Yii::t('app', 'Back').'</span> </div>', [
                                'dashboard/index',
                            ]);
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                        <?php
                        if($nextUrl) {
                            echo '<a href="'.$nextUrl.'"><div class="button-style align-h-middle"><span class="style-3 skip">'.Yii::t('app', 'Skip').'</span></div></a> ';
                            /*echo Html::a('<div class="button-style"><span class="style-3 skip">'.Yii::t('app', 'Skip').'</span> </div>', [
                                'category/view-by-step',
                                'step' => $nextOrder,
                            ]);*/
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                    <?php if($isChecked): ?>
                        <a href="<?= $nextUrl ?>"><div class="button-style"><span class="style-2 next check-is-voted"><?= Yii::t('app', 'Next') ?></span> </div></a>
                    <?php else: ?>
                        <div class="button-style"><span class="style-2 next check-is-voted"><?= Yii::t('app', 'Next') ?></span> </div>
                    <?php endif; ?>

                        <?php /*
                    if($nextOrder){
                        echo Html::a('<div class="button-style"><span class="style-2 next">Next</span> </div>', [
                            'category/view-by-step',
                            'step' => $nextOrder,
                        ],['onclick' => "checkIsVoted(function(voted){ 
                                if(voted == 1){
                                    return true;
                                } else {
                                    return false;
                                }
                            });"]);
                    } else {
                        echo Html::a('<div class="button-style"><span class="style-2 next">Next</span> </div>', [
                            'voter/votes-summary',
                        ]);
                    }*/
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3">
                <div class="sidebar">
                    <ul class="award-categories">
                        <?php
                        foreach ($awardCategories as $awardCategory):
                            ?>
                            <li>
                                <div class="left-circle-wrapper"><div class="<?= (\yii\helpers\ArrayHelper::isIn($awardCategory->id, $votedCategories) ) ? 'completed glyphicon glyphicon-ok' : 'circle' ?>"></div></div>
                                <div class="right-text-wrapper">
                                    <?php
                                    echo Html::a($awardCategory->name, [
                                        'category/view-by-step',
                                        'step' => $awardCategory->order,
                                    ]);
                                    ?>
                                </div>
                            </li>
                            <?php
                        endforeach;
                        ?>
                        <li>
                            <div class="left-circle-wrapper"><div class="circle"></div></div>
                            <div class="right-text-wrapper">
                            <?php
                            echo Html::a(Yii::t('app', 'Summary'), [
                                'voter/votes-summary',
                            ],
                                [
                                    'style' => 'font-weight:bold;'
                                ]);
                            ?>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php

$strConfirm = Yii::t("app", "Confirm");
$strConfirmMsg = Yii::t("app", "You have not voted for this category. Are you sure want to continue?");
$strYesContinue = Yii::t("app", "Yes, Continue");
$strNoBack = Yii::t("app", "No, Go Back");
$voteUrl = Url::to(['category/vote']);
$checkVoteUrl = Url::to(['category/check-is-voted']);

$script = <<< JS
$('.award-category .award-image').click(function(){
    var curImage = this;
    $.ajax({
        type  :'POST',
        cache  : false,
        url  : '$voteUrl',
        data : {
            'nomineeId' : $(curImage).data('nominee-id'),
            'categoryId' : $model->id
        },
        success : function(data){
            if(data == 1){
                $('.award-image').find('.overlay-selected').remove();//Remove the existing overlay everywhere
                $('.award-image').removeClass('award-image-selected');//Remove the selected class on the div
               
                //$(curImage).find('.image').after(getOverlayContent(curImage));//Add overlay after the image
                $(curImage).find('.image').append(getOverlayContent(curImage));//Add overlay after the image
                
                $(curImage).addClass('award-image-selected');//Add selected class
            } else if(data == 2){
                $('.award-image').find('.overlay-selected').remove();//Remove the existing overlay everywhere
                $('.award-image').removeClass('award-image-selected');//Remove the selected class on the div
            } else {
                
            }
        }
    });return false;
});

//Get overlay content
function getOverlayContent(curElem) {
    /*var overlaySizes = getOverlaySizes(curElem);
    return '<div class="overlay-selected" style="width:' + overlaySizes.width + 'px;height:' + overlaySizes.height + 'px; left:'+ overlaySizes.leftMargin +'px;"><div class="done-image"></div></div>';*/
    return '<div class="overlay-selected"><div class="done-image"></div></div>';
}

//Get overlay height width styles
function getOverlaySizes(curElem){
    var curWidth = $(curElem).find('.image').width();
    var curHeight = $(curElem).find('.image').height();
    
    var parentWidth = $(curElem).parent().width();
    var parentPadding = $(curElem).parent().css('paddingLeft');
    var leftMargin = (parentWidth - curWidth)/2;
    leftMargin = leftMargin+parseInt(parentPadding);
    //console.log($(curElem).find('.image'));
    return {
        width : curWidth,
        height : curHeight,
        leftMargin : leftMargin
    }
}

//Add correct sizes for existing overlays
if($('.overlay-selected').length){
    /*var overlaySizes = getOverlaySizes($('.award-category .award-image-selected'));
    $('.award-image-selected .overlay-selected').css({width : overlaySizes.width+'px', height : overlaySizes.height+'px', left : overlaySizes.leftMargin });*/
    return '<div class="overlay-selected"><div class="done-image"></div></div>';
}

//Check if the voted for the current category
$('.check-is-voted').click(function(){
    var that = this;
    $(that).prepend('<span class="ajax-loader">&nbsp;</span>').attr("disabled", "disabled");
    $.ajax({
        type  :'POST',
        cache  : false,
        url  : '$checkVoteUrl',
        data : {
            'categoryId' : $model->id
        },
        success : function(data){
            if(data == 1){
                window.location.href = '$nextUrl';
            } else {
                $(that).find('.ajax-loader').remove();
                loadConfirmNextDialog();
            }
        }
    });
});

function loadConfirmNextDialog() {
    BootstrapDialog.show({
        title: "$strConfirm",
        message: "$strConfirmMsg",
        buttons: [{
            label: "$strYesContinue",
            id: 'confirm-go-next',
            action: function (dialog) {
                window.location.href = '$nextUrl';
            }
        }, {
            label: "$strNoBack",
            id: 'close-for-vote',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
}

JS;
$this->registerJs($script, View::POS_READY);
