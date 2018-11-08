<style type="text/css">
    #myinformation{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_USERS_PLURAL');
$this->pageSubTitle = 'You can make amend your details here';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myInformationClientMenu', array('selectedMenu' => 'serviceUsers')); ?>
    </div>
</div>

<div class="row">
    <div class="large-6 medium-8 small-12 columns">
        
        <?php
        $this->widget('ServiceUsersWidget', array('client' => $client, 'serviceUsers' => $serviceUsers, 'scenario' => ServiceUsersWidget::EDIT_SCENARIO));
        ?>
    </div>
</div>