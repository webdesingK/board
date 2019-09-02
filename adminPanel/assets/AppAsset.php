<?php

    namespace adminPanel\assets;

    use yii\web\AssetBundle;

    /**
     * Main adminPanel application asset bundle.
     */
    class AppAsset extends AssetBundle {

        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            'css/all.min.css' // fontawesome icons
        ];
        public $js = [
            'js/main.js'
        ];
        public $depends = [
            'yii\bootstrap4\BootstrapPluginAsset',
        ];
    }
