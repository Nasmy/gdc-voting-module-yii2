<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway:600,500,400,700,800%7CCardo:400&amp;subset=latin,latin-ext">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container main-login-area">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12"></div>
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"></div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="login-form-wrapper">
                            <?php
                            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                                if ($key == 'error') {
                                    $key = 'danger';
                                }
                                echo '<p></p>';
                                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
                            }
                            ?>
                            <?php echo $content ?>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"></div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12"></div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
