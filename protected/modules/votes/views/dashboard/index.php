<?php
use yii\helpers\Html;

?>
<div class="container main-content-area">
    <div class="row">
        <div class="col-lg-12">
            <div class="welcome-heading"><?= Yii::t('app', 'Welcome');?></div>
            <div class="welcome-content-p1"><p><?= Yii::t('app', 'Voter welcome text');?></p></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="welcome-heading"><?= Yii::t('app', 'Instruction');?></div>
            <div class="welcome-content-p2"><p><?= Yii::t('app', 'Voter instruction text');?></p></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!--div class="welcome-note"><?//= Yii::t('app', 'Note: After you finalised the voting process you can’t access to this system again.');?>
            </div-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row text-center">
                <?php
                echo Html::a('<div class="button-style"><span class="style-2">'.Yii::t('app', 'Start Now').'</span></div>', [
                    'category/view-by-step',
                    'step' => $minOrderCategory->order,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>