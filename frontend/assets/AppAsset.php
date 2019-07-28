<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main adminPanel application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
			'css/libs.css',
			'css/main.css'
    ];
    public $js = [
			'js/common.js'
    ];
    public $depends = [
			'yii\web\JqueryAsset'
    ];
}
