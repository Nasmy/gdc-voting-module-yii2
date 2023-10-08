<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

?>
<div class="container">
    <div class="row">
        <?php
        if(!Yii::$app->session->getAllFlashes() ):
        ?>
        <div class="site-error">
            <div class="alert alert-danger">
                <?= $errorMessage ?>
            </div>
        </div>
        <?php
        endif;
        ?>
    </div>
</div>
