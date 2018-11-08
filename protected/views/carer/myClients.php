<style type="text/css">
    #myclients{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'My Clients');
$this->pageSubTitle = 'Respond to clients requests';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <p>
           Clients will book you online after you have agreed on a job.
        </p>
        <br>
        <?php
        foreach ($carer->conversations as $conversation) {
            $this->renderPartial('/conversation/_conversation', array('conversation' => $conversation, 'viewer' => Constants::USER_CARER));
        }
        ?>
    </div>
</div>