<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="keywords" content="<?php echo CHtml::encode($this->keyWords); ?>" />
        <meta name="description" content="<?php echo CHtml::encode($this->description); ?>" />
        <meta name="copyright" content="Copyright Direct Homecare Ltd 2010-2013. All Rights Reserved." />
        <?php
        $path = Yii::getPathOfAlias('application.modules.admin.css');

        $assetsUrl = Yii::app()->getAssetManager()->publish($path . '/mainAdmin.css');

        echo '<link rel="stylesheet" type="text/css" href="' . $assetsUrl . '"  />';

        $assetsUrl = Yii::app()->getAssetManager()->publish($path . '/screen.css');

        echo '<link rel="stylesheet" type="text/css" href="' . $assetsUrl . '"  />';

        $assetsUrl = Yii::app()->getAssetManager()->publish($path . '/form.css');

        echo '<link rel="stylesheet" type="text/css" href="' . $assetsUrl . '"  />';
        ?>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>
    <body>
        <?php
        Yii::app()->clientScript->registerCoreScript("jquery");
        Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
        ?>       
        <?php echo $content; ?>
    </body>
</html>