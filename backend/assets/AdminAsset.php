<?php

    namespace backend\assets;

    use yii\web\AssetBundle;

    /**
     * Main backend application asset bundle.
     */
    class AdminAsset extends AssetBundle {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
//            'css/libs.css',
            'css/main.css'
        ];
        public $js = [
//			'js/common.js'
        ];
        public $depends = [
//            'yii\bootstrap\BootstrapAsset',
//            'yii\bootstrap\BootstrapPluginAsset',
//			'yii\web\JqueryAsset'
        ];
    }
