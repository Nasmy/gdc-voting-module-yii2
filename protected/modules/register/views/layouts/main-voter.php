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

                <?php
                if(isset($this->params['awardCategories'])):
                ?>
                <div class="header-circle-line-wrapper">
                    <div class="header-circle-wrapper">
                    <?php
                        foreach ($this->params['awardCategories'] as $awardCategory):
                            $circleColouredClass = (isset($this->params['step']) && ($this->params['step'] == $awardCategory->order || $this->params['step'] > $awardCategory->order)) ? 'header-circle-coloured' : '';
                            /*echo Html::a('<div class="header-circle '.$circleColouredClass.'"></div>', [
                                'category/view-by-step',
                                'step' => $awardCategory->order,
                            ]);*/
                            echo '<a><div class="header-circle '.$circleColouredClass.'"></div></a>';
                        endforeach;
                    ?>
                    </div>
                    <div class="header-line-wrapper">
                        <div class="header-line"></div>
                    </div>
                </div>
                <?php
                endif;
                ?>
            </div>

        </div>
    </div>
</nav>

<?= $content ?>

<footer class="footer">
    <div class="container">
        <ul class="footer-menu">
            <li class="copyright"><a href="<?= Yii::$app->params['publicSiteUrl'] ?>" target="_blank"> <?= Yii::$app->params['productName'] ?> </a> <?= Yii::$app->params['copyright'] ?></li>
        </ul>
    </div>
</footer>


<?php
$routeUrl = \yii\helpers\Url::toRoute('default/change-language');

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
