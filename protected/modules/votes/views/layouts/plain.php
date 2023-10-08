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
<div class="container main-login-area"  style="padding-top: 50px;">
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        if ($key == 'error') {
            $key = 'danger';
        }
        echo '<p></p>';
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        echo '<table align="center" style="margin-top: 20px"><tr>
        <td><img src="https://www.globesdecristal.com/wp-content/uploads/2018/11/logo_GDC_2018_degrade_centre-1.png" style="width: 500px"></td>
        </tr></table>';

    }
    ?>
    <?php echo $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
