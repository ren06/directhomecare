<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<!--[if IE 10]><html class="ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="format-detection" content="telephone=no" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/normalize.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation.min.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation-icons.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation-rc.css" />
        <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo Yii::app()->request->baseUrl; ?>/images/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/images/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/images/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->baseUrl; ?>/images/apple-touch-icon-precomposed.png">
        <meta name="keywords" content="<?php echo CHtml::encode($this->keyWords); ?>" />
        <meta name="description" content="<?php echo CHtml::encode($this->description); ?>" />
        <meta name="copyright" content="Copyright Direct Homecare Ltd 2010-<?php echo date("Y"); ?>. All rights reserved." />
        <?php
        if (Yii::app()->params['test']['useMouseFlow'] == true) {
            ?>
            <!-- Google Analytics -->
            <script type="text/javascript">
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-49833720-1', 'directhomecare.com');
                ga('require', 'displayfeatures');
                ga('require', 'linkid', 'linkid.js');
                ga('send', 'pageview');

            </script>
            <?php
        }
        ?>
    </head>
    <body>
        <div class="off-canvas-wrap">
            <div class="inner-wrap">
                <?php
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerCoreScript('jquery.ui');

                include Yii::app()->basePath . '/views/layouts/_header.php';

                echo $content;

                include Yii::app()->basePath . '/views/layouts/_footer.php';
                ?>
                <!-- Off canvas End. Must be placed after the footer. -->
                <a class="exit-off-canvas"></a>
            </div>
        </div>
        <!--
        Commented to avoid conflicts with Yii JQ
        Keep the 2 other foundations scripts
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery.js"></script>
        -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/modernizr.js"></script>

        <?php
        Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . '/js/qTip/jquery.qtip.min.js'), CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . '/js/qTip/jquery.qtip.min.css'));
        ?>

        <script>
            $(document).foundation();
        </script>
        <?php
        if (Yii::app()->params['test']['useMouseFlow'] == true) {
            ?>
            <!-- Mouse Flow -->
            <script type="text/javascript">
                var _mfq = _mfq || [];
                (function() {
                    var mf = document.createElement("script");
                    mf.type = "text/javascript";
                    mf.async = true;
                    mf.src = "//cdn.mouseflow.com/projects/bf6c3182-a66d-4849-b10a-8ec87a47c6c5.js";
                    document.getElementsByTagName("head")[0].appendChild(mf);
                })();
            </script>
        <?php } ?>
    </body>
</html>