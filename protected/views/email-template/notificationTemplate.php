<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>LES GLOBES DE CRISTAL</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #ffffff; padding-top: 30px; font-family: 'Helvetica', Arial, sans-serif;">

        <table align="center" cellpadding="0" cellspacing="0" width="620" style="border-collapse: collapse;">
            <tr>
                <td>

                    <!-- Header -->
                    <table width="100%" style="width:92%!important; background-color: #121212; padding: 2%; border-bottom: 2px solid #d4b469; margin: 0;">
                        <tr>
                            <td align="center">
                                <img src="<?= Yii::$app->params['logoUrlEmail'] ?>" title="<?= Yii::$app->params['productName'] ?>" alt="<?= Yii::$app->params['productName'] ?>" width="200" style="width: 200px;">
                            </td>
                        </tr>
                    </table>

                    <!-- Content -->
                    <table width="100%" style="width:92%!important; border-bottom: 2px solid #d4b469; background-color: #fff; padding: 4%; margin: 0;">
                        <tr>
                            <td style="font-size: 14px; color: #464646; text-align: left;">
                                <?php echo $content ?>
                                <?php
                                $emailAssetsDomain = 'https://www.globesdecristal.com';
                                ?>
                                <p>
                                    <img src="<?= $emailAssetsDomain ?>/wp-content/uploads/2018/11/email-template-logo.png"
                                         title="Les globes de Cristal" width="150" alt="Les globes de Cristal" style="width: 150px;">
                                </p>
                                <p style="font-family: Georgia; font-size: 12px; line-height: 20px; color: #999999;">
									<span style="color: #000;"><strong>Relations presse et Relations publiques</strong></span><br/>
                                    <!--<span style="color: #000;"><strong>Nathalie DELACROIX</strong></span><br/>-->
                                    <!--<span style="display: block;margin-top: -5px;margin-bottom: 8px;">Relations presse et Relations publiques<br/></span>-->									
                                    <!--<span style="line-height: 16px;display: block;margin-top: -5px;margin-bottom: 8px;">+33 (0)6 46 85 47 56<br/>-->
                                    <a href="mailto:presse@globesdecristal.com">presse@globesdecristal.com</a><br/>
                                    30, rue de Miromesnil<br/>
                                    75008 Paris<br/></span>
                                    <a href="<?= $emailAssetsDomain ?>" target="_blank" style="font-size: 10px;">www.globesdecristal.com</a><br/>
                                    <span style="color: #000; font-family: Arial;"><strong>Suivez notre actualité</strong></span> <br/>
                                    <a href="https://twitter.com/GlobesDeCristal" target="_blank"><img src="<?= $emailAssetsDomain ?>/wp-content/uploads/2016/11/tw.png" width="16"
                                                                                                       style="width: 16px;"></a>
                                    <a href="https://www.facebook.com/LesGlobesDeCristal/?fref=ts" target="_blank"><img src="<?= $emailAssetsDomain ?>/wp-content/uploads/2016/11/fb.png" width="16"
                                                                                                                        style="width: 16px;"></a>
                                    <a href="https://www.instagram.com/globesdecristal" target="_blank"><img src="<?= $emailAssetsDomain ?>/wp-content/uploads/2016/11/ins.png" width="16"
                                                                                                             style="width: 16px;"></a>
                                </p>
                                <br/>
                                <p style="font-family: Georgia; font-size: 12px; line-height: 20px;"><strong>Les Globes de Cristal</strong> / <span style="color: #999999;"> Lundi 04 février 2019</span></p>
                            </td>
                        </tr>
                    </table>

                    <!-- Footer -->
                    <!--
					<table width="100%" style="width:92%!important; background-color: #121212; padding: 10px; margin: 0;">
                        <tr>
                            <td style="font-family: arial, helvetica, verdana, sans-serif; text-align: center; font-size: 12px; padding: 10px; color: #ffffff;">
								<p>
                                    <?php //= Yii::t('mail', 'Tel: ') . Yii::$app->params['telephone'] ?> - <?php //= Yii::t('mail', 'Email: ') ?><a href="mailto:<?php //= Yii::$app->params['supportEmail'] ?>" style="color: #4375ff;"><?php //= Yii::$app->params['supportEmail'] ?></a>
								</p>
                                <p><?php //= Yii::t('mail', 'LES GLOBES DE CRISTAL © {year}. All Rights Reserved.', ['year' => date('Y')]) ?></p>
                            </td>
                        </tr>
                    </table>
					-->

                </td>
            </tr>
        </table>

    </body>
</html>