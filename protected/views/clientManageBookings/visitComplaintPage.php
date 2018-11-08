<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_COMPLAINT');
$this->pageSubTitle = 'It seems that we must solve an issue !';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('/common/_backTo', array('scenario' => $scenario)); ?>
        <h4><?php echo Yii::t('texts', 'HEADER_VISIT_REFERENCE') . '&#58;&#160;' . BusinessLogic::getReference($mission); ?></h4>
        <p>
            <?php
            echo Calendar::convert_DBDateTime_DisplayDateTimeText($mission->start_date_time) . '&#160;' . Yii::t('texts', 'LABEL_TO') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($mission->end_date_time);
            echo '<br>' . Yii::t('texts', 'LABEL_CARER') . '&#58;&#160;';
            if (isset($mission->assignedCarer)) {
                $text = $mission->assignedCarer->fullName;
            } else {
                $text = Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED');
            }
            echo $text;
            ?>
        </p>
        <hr>
        <h4><?php echo Yii::t('texts', 'HEADER_COMPLAINT_BY_CARER'); ?></h4>
        <div id="complaint_carer">
            <?php
            $this->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaintCarer, 'user' => Constants::USER_CARER));
            ?>
        </div>
        <br>
        <hr>
        <h4><?php echo Yii::t('texts', 'HEADER_COMPLAINT_BY_CLIENT'); ?></h4>
        <div id="complaint_client">
            <?php
            $this->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaintClient, 'user' => Constants::USER_CLIENT));
            ?>
        </div>
    </div>
    <div id="hook" style="display:none">
        <div id="dialog"></div>
    </div>
</div>
