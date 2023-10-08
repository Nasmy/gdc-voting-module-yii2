<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use app\models\Register;
/* @var $this yii\web\View */
/* @var $model app\models\Category */

//$this->title = $model->name;
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
                <?php
                    $isChecked = false;
                   
                ?>


                <div class="row">
               <?php 
/// Switch  category from ......................................................

               if($this->params['step']==1)
               { ?>

                 <?php 

                 $form = ActiveForm::begin([
                'id' => 'dynamic-form111',
                'enableClientValidation' => true,
                'action'=>'create',
               ]); ?>

                <?= $form->field($register, 'title') ?>
                <?= $form->field($register, 'film') ?>
                <?= $form->field($register, 'producer') ?>

                <?= Html::hiddenInput('Register[step]', $this->params['step']); ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>

              <?php

               } 
//..........................................................................................
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
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                    <?php if($isChecked): ?>
                        <a href="<?= $nextUrl ?>"><div class="button-style"><span class="style-2 next check-is-voted"><?= Yii::t('app', 'Next') ?></span> </div></a>
                    <?php else: ?>
                        <div class="button-style"><span class="style-2 next check-is-voted"><?= Yii::t('app', 'Next') ?></span> </div>
                    <?php endif; ?>

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

    $(document).ready(function () {
        $('body').on('beforeSubmit', 'form#dynamic-form111', function () {
            alert('ddddddddddd');
            var form = $(this);
            // return false if form still have some validation errors
            if (form.find('.has-error').length) 
            {
                return false;
            }
            // submit form
            var actionURL='/gdc/index.php/register/category/create';
            $.ajax({
            url    : actionURL,
            type   : 'POST',
            data   : form.serialize(),
            success: function (response) 
            {
                var getupdatedata = $(response).find('#filter_id_test');
                // $.pjax.reload('#note_update_id'); for pjax update
                $('#yiiikap').html(getupdatedata);
                //console.log(getupdatedata);
            },
            error  : function () 
            {
                console.log('internal server error');
            }
            });
            return false;
         });
    });


JS;
$this->registerJs($script, View::POS_READY);




