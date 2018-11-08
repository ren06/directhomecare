<style type="text/css">
    #myinformation{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_LOCATIONS');
$this->pageSubTitle = 'You can make amend your details here';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myInformationClientMenu', array('selectedMenu' => 'serviceLocations')); ?>
    </div>
</div>

<div class="row">
    <div class="large-6 medium-8 small-12 columns">
        <p class="rc-note">
            <?php echo Yii::t('texts', 'FIELDS_REQUIRED'); ?>
        </p>
        <div id="errorMessage" class="rc-error"></div>
        <?php
        $this->widget('ServiceLocationsWidget', array('client' => $client, 'serviceLocations' => $serviceLocations, 'scenario' => ServiceLocationsWidget::EDIT_SCENARIO));
        ?>
    </div>
</div>