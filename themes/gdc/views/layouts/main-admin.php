<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$title = '' == $this->title ? Yii::$app->params['productName'] : Yii::$app->params['productName'] . ' - ' . $this->title;
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
                    <div class="fr">FR</div>
                    <span class="dot"></span>
                    <div class="en">EN</div>
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
                                // Dashboard
                                ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/dashboard/admin-dashboard'], 'visible' => Yii::$app->user->can('Dashboard.Dashboard')],
                                // Category
                                ['label' => Yii::t('app', 'Categories'), 'url' => ['/category/index'], 'visible' => Yii::$app->user->can('Category.Index')],
                                // Nominee
                                ['label' => Yii::t('app', 'Nominees'), 'url' => ['/nominee/index'], 'visible' => Yii::$app->user->can('Nominee.Index')],
                                // Voter menu
                                //[
                                //    'label' => Yii::t('app', 'Voters'),
                                //    'visible' => Yii::$app->user->canList(['Voter.Index', 'Voter.Votes']),
                                //    'items' => [
                                //        ['label' => Yii::t('app', 'Voters'), 'url' => ['/voter/index'], 'visible' => Yii::$app->user->can('Voter.Index')],
                                //        ['label' => Yii::t('app', 'Votes'), 'url' => ['/voter/votes'], 'visible' => Yii::$app->user->can('Voter.Votes')],
                                //    ],
                                //],
                                // Voter
                                ['label' => Yii::t('app', 'Voters'), 'url' => ['/voter/index'], 'visible' => Yii::$app->user->can('Voter.Index')],
                                // Voter
                                //['label' => Yii::t('app', 'Voter Votes'), 'url' => ['/voter/votes'], 'visible' => Yii::$app->user->can('Voter.Votes')],
                                //['label' => '<span class="glyphicon glyphicon-usd"></span> ' . Yii::t('app', 'Payments'), 'url' => ['/payment/index'], 'visible' => Yii::$app->user->can('Payment.Index')],
                                //[
                                //    'label' => Yii::t('app', 'System'),
                                //    'visible' => Yii::$app->user->canList(['Permission.Index', 'Role.Index', 'User.Index']),
                                //    'items' => [
                                //        ['label' => Yii::t('app', 'Permissions'), 'url' => ['/permission/index'], 'visible' => Yii::$app->user->can('Permission.Index')],
                                //        ['label' => Yii::t('app', 'Roles'), 'url' => ['/role/index'], 'visible' => Yii::$app->user->can('Role.Index')],
                                //        ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index'], 'visible' => Yii::$app->user->can('User.Index')],
                                //    ],
                                //],
								// User
								['label' => Yii::t('app', 'Users'), 'url' => ['/user/index'], 'visible' => Yii::$app->user->can('User.Index')],
                                [
                                    'label' => Yii::$app->user->identity->firstName . ' ' . Yii::$app->user->identity->lastName,
                                    'items' => [
                                        //['label' => Yii::t('app', 'My Account'), 'url' => ['/user/my-account'], 'visible' => Yii::$app->user->can('User.MyAccount')],
                                        //['label' => Yii::t('app', 'Change Password'), 'url' => ['/user/change-password'], 'visible' => Yii::$app->user->can('User.ChangePassword')],
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

<div class="container main-content-area main-admin">
    <div class="page">
        <div class="card-breadcrumb">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
            ?>
        </div>
        <div class="card">
            <div id="statusMsg"></div>
            <?php
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                if ($key == 'error') {
                    $key = 'danger';
                }
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            }
            ?>

            <?php if (null != $this->pageTitle): ?>
                <div class="card-header">
                    <h2><?= $this->pageTitle ?>
                        <?php if (null != $this->pageTitleDescription): ?>
                            <small><?= $this->pageTitleDescription ?></small>
                        <?php endif; ?>
                    </h2>
                </div>
            <?php endif; ?>

        </div>
    </div>
<?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <ul class="footer-menu">
            <li class="copyright"><a href="<?= Yii::$app->params['publicSiteUrl'] ?>" target="_blank"> <?= Yii::$app->params['productName'] ?> </a> <?= Yii::$app->params['copyright'] ?></li>
        </ul>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
