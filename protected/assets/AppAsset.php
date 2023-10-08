<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /*'themes/default/css/bootstrap.min.css',
        'extensions/fancybox/source/jquery.fancybox.css',
        'themes/default/css/style.css',*/
        'themes/gdc/css/main.css',
    ];
    public $js = [
        /*'themes/default/js/bootstrap.min.js',
        'themes/default/js/bootbox.min.js',
        'js/Util.js',
        'extensions/fancybox/source/jquery.fancybox.pack.js',
        'extensions/toastMessage/jquery.toaster.js',*/
        'themes/gdc/bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'themes/gdc/bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js',
        'themes/gdc/node_modules/chart.js/dist/Chart.bundle.min.js',
        'themes/gdc/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
