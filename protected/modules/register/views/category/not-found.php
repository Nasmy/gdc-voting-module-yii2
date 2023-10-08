<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->pageTitle = Yii::t('app', 'Access Denied');
//$this->pageTitleDescription = Yii::t('app', 'Access denied');
$this->params['breadcrumbs'][] = Yii::t('app', 'Error');
?>
<div class="container main-content-area award-category">
    <div class="row">
        <div class="site-error">
            <div class="alert alert-danger">
                <?= nl2br(Yii::t('app', 'Award category not found.')) ?>
            </div>
        </div>
    </div>
</div>
