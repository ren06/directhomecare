<?php
$this->pageTitle = Yii::t('texts', 'SITE_ERROR_TITLE') . $code;
$this->pageSubTitle = 'Oops it seems that there is an error !';
$this->keyWords = 'error page';
$this->description = 'Error page.';
?>

<div class="row">
    <div class="large-12 columns">
        <div data-alert class="alert-box alert radius">
                <?php echo CHtml::encode($message); ?>
            <a href="#" class="close">&times;</a>
        </div>
        <h3>Sorry</h3>
        <p>
            We are sorry to inform you that this page is not available.
        </p>
        <h3>Back</h3>
        <p>
            Press the back button on your browser to return to the previous page.
        </p>
        <h3>Help</h3>
        <p>
            If you need to some help, visit the <?php echo CHtml::link('contact page', array('site/contact')); ?>.
        </p>
    </div>
</div>