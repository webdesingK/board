<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
			'css/libs.min.css',
			'css/main.min.css'
    ];
    public $js = [
			'js/common.js'
    ];
    public $depends = [
			'yii\web\JqueryAsset'
    ];
}
