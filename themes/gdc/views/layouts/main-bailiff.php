<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$title = '' == $this->title ? Yii::$app->params['productName'] : Yii::$app->params['productName'] . ' - ' . $this->title;
$currentLanguage = (Yii::$app->session->get('languageId')) ? Yii::$app->session->get('languageId') : Yii::$app->params['defaultLanguage'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?= Yii::$app->view->theme->baseUrl ?>/img/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway:600,500,400,700,800%7CCardo:400&amp;subset=latin,latin-ext">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <a class="navbar-brand" href="#"><img src="<?= Yii::$app->view->theme->baseUrl ?>/images/logo.png"
                                                      class="main-logo"/> </a>
            </div>

            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                <div class="language-seletor-wrapper">
                    <div class="fr language <?= ($currentLanguage == 'fr-FR') ? 'selected' : '' ?>" data-language-id="fr-FR">FR</div>
                    <span class="dot"></span>
                    <div class="en language <?= ($currentLanguage == 'en-GB') ? 'selected' : '' ?>" data-language-id="en-GB">EN</div>
                </div>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div id="navbar" class="collapse navbar-collapse">
                    <?php
                    if (!Yii::$app->getUser()->isGuest):
                        echo Nav::widget([
                            'options' => ['class' => 'nav navbar-nav navbar-right'],
                            'activateItems' => true,
                            'activateParents' => true,
                            'encodeLabels' => false,
                            'items' => [
                                ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/dashboard/bailiff-dashboard'], 'visible' => Yii::$app->user->can('Dashboard.BailiffDashboard')],
                                ['label' => Yii::t('app', 'Detailed Votes'), 'url' => ['/category/detailed-votes'], 'visible' => Yii::$app->user->can('Category.DetailedVotes')],
                                ['label' => Yii::t('app', 'Voters'), 'url' => ['/voter/votes'], 'visible' => Yii::$app->user->can('Voter.Votes')],
                                [
                                    'label' => Yii::$app->user->identity->firstName . ' ' . Yii::$app->user->identity->lastName,
                                    'items' => [
                                        ['label' => Yii::t('app', 'My Account'), 'url' => ['/user/my-account'], 'visible' => Yii::$app->user->can('User.MyAccount')],
                                        ['label' => Yii::t('app', 'Change Password'), 'url' => ['/user/change-password'], 'visible' => Yii::$app->user->can('User.ChangePassword')],
                                        ['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                                    ],
                                ],
                            ],
                        ]);
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="container main-content-area baillif">
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        if ($key == 'error') {
            $key = 'danger';
        }
        echo '<p></p>';
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
    }
    ?>
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <ul class="footer-menu">
            <li class="copyright"><a href="<?= Yii::$app->params['publicSiteUrl'] ?>" target="_blank"> <?= Yii::$app->params['productName'] ?> </a> <?= Yii::$app->params['copyright'] ?></li>
        </ul>
    </div>
</footer>

<?php
$routeUrl = \yii\helpers\Url::toRoute('site/change-language');

$script = <<< JS
$('.language-seletor-wrapper .language').click(function(){
    $.ajax({
        type  :'POST',
        cache  : false,
        url  : '$routeUrl',
        data : {
            'languageId' : $(this).data('language-id'),
        },
        success : function(data){
            if(data == true){
                location.reload();
            } else {
                return false;
            }
        }
    });
})
JS;
$this->registerJs($script, \yii\web\View::POS_READY);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>