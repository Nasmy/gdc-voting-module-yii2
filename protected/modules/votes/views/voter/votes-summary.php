<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="container main-content-area voted-summery">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="heading"><?//= Yii::t('app', 'Summary') ?><?= Yii::t('app', 'Your voting summary') ?></div>
                <!--div class="sub-heading"><?//= Yii::t('app', 'Your voting summary') ?></div-->

                <div class="row voted-item-wrapper">
                    <?php
                    $isVotedArr = [];
                    foreach ($categories as $category):
                        $isVoted = false;
                        $nomineeName = Yii::t('app', 'Not Selected');

                        $votes = $category->getVotes()->where(['voterId' => Yii::$app->user->identity->id])->one();

                        if (!empty($votes)) {

                            $nominee = $votes->getNominee()->one();
                            $isVoted = true;
                            $nomineeName = $nominee->name;
                            $imageSrc = $nominee->imageWebPath;

                        }

                        $categoryStep = Url::to(['category/view-by-step', 'step' => $category->order]);
                        $isVotedArr[] = $isVoted;
                        ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 summary-block-wrapper">
                            <a href="<?= $categoryStep ?>">
                                <div class="summary-image-wrapper">
                                    <?php
                                    if ($isVoted):
                                        ?>
                                        <div class="image">
                                            <img
                                                src="<?= $imageSrc ?>"/>
                                            <div class="done-image"></div>
                                        </div>
                                        <?php
                                    else:
                                        ?>
                                        <div class="image image-not-selected">
                                            <div class="question-image"></div>
                                        </div>
                                        <?php
                                    endif;
                                    ?>
                                </div>
                                <div class="summary-image-description">
                                    <div class="category-title"><?= $category->name ?></div>
                                    <div class="title"><?= $nomineeName ?></div>
                                </div>
                            </a>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>

                <div class="row prev-next-skip-btns">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
                        <?php
                        echo Html::a('<div class="button-style"><span class="style-2 back">' . Yii::t('app', 'Back') . '</span></div>', [
                            'category/view-by-step',
                            'step' => $maxOrderCategory->order,
                        ]);
                        ?>
                    </div>

                    <?php

                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        <div class="button-style"><span
                                class="style-2 confirm-and-exit"><?= Yii::t('app', 'Confirm & exit') ?></span></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php

$srtConfirmMsg = (in_array(false, $isVotedArr)) ? Yii::t("app", "You have not voted for all the categories, if you submit now you will not be able to change your choices again. Are you sure?"): Yii::t("app", "If you submit now you will not be able to change your choices again. Are you sure?");
$srtVotedMsg = Yii::t("app", "Your votes succesfully submitted to the system. Thank you.");
$srtConfirmMsgAgain = Yii::t("app", "Are you sure you want to submit? Please confirm again.");

$srtYesConfirm = Yii::t("app", "Yes, Confirm");
$srtYesSubmit = Yii::t("app", "Yes, Submit");
$srtNoBack = Yii::t("app", "No, Go Back");
$srtSuccess = Yii::t("app", "Success");

$srtConfirm = Yii::t("app", "Confirm");
$srtConfirmAgainExit = Yii::t("app", "Confirm Again & Exit");
$srtExit = Yii::t("app", "Exit");

$strError = Yii::t("app", "Error occurred during submit your vote details. Please contact the administrator");

$voterConfirm = Url::to(['voter/voter-confirm']);

$script = <<< JS
//Load dialog model to show the confirmation- Bootstrap new version
$(document).ready(function(){
    
    //Load bootstrap dialog - 1st step confirmation
    $('.confirm-and-exit').click(function () {
        BootstrapDialog.show({
            title: '$srtConfirm',
            message: "$srtConfirmMsg",
            buttons: [{
                label: '$srtYesSubmit',
                id: 'confirm-vote-submit',
                action: function (dialog) {
                    dialog.close();
                    loadConfirmExitDialog();
                }
            }, {
                label: '$srtNoBack',
                id: 'close-vote-submit',
                action: function (dialog) {
                    dialog.close();
                }
            }]
        });
    });
        
    //Load bootstrap dialog - 2nd step confirmation
    function loadConfirmExitDialog() {
        BootstrapDialog.show({
            title: '$srtConfirmAgainExit',
            message: "$srtConfirmMsgAgain",
            buttons: [{
                label: '$srtYesConfirm',
                id: 'confirm-vote-submit-again',
                action: function (dialog) {
                    $(this).prepend('<span class="ajax-loader">&nbsp;</span>').attr("disabled", "disabled");
                    updateVoterDetails(function(isUpdated){
                        if(isUpdated){
                            $(this).find('.ajax-loader').remove();
                            dialog.close();
                        } else {
                            alert('$strError');
                        }
                        
                    });
                }
            }, {
                label: '$srtNoBack',
                id: 'close-vote-submit-again',
                action: function (dialog) {
                    dialog.close();
                }
            }]
        });
    }
       
    //Load dialog model to show success msg - Bootstrap new version
    function loadConfirmedDialog() {
        BootstrapDialog.show({
            title: '$srtSuccess',
            message: "$srtVotedMsg",
            closable: false,
            buttons: [{
                label: '$srtExit',
                id: 'close-vote-submit',
                action: function (dialog) {
                    dialog.close();
                    window.location.href = 'exit';
                }
            }]
        });
    }
    
    //Submit voter details via ajax
    function updateVoterDetails(callback){
        $.ajax({
            type  :'POST',
            cache  : false,
            url  : '$voterConfirm',
            data : {
                'confirmed' : true
            },
            success : function(data){
                if(data == true){
                    callback(1);
                    loadConfirmedDialog();
                } else {
                    callback(0);
                }
            }
        });
    }
 
})
JS;
$this->registerJs($script, View::POS_READY);