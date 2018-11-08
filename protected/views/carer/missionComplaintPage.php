<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_COMPLAINT') ?>

<h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>
<br />
<?php
$this->renderPartial('/common/_backTo', array('scenario' => $scenario));
?>
<div class="rc-container-40">
    <br />
    <h3><?php echo Yii::t('texts', 'HEADER_MISSION_REFERENCE') . '&#58;&#160;' . BusinessLogic::getReference($mission); ?></h3>
    <p>
        <?php
        echo Calendar::convert_DBDateTime_DisplayDateTimeText($mission->start_date_time) . '&#160;' . Yii::t('texts', 'LABEL_TO') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($mission->end_date_time);
        echo '<br />' . Yii::t('texts', 'LABEL_CARER') . '&#58;&#160;';
        if (isset($mission->assignedCarer)) {
            $text = $mission->assignedCarer->fullName;
        } else {
            $text = Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED');
        }
        echo $text;
        ?>
    </p>
    <hr>
    <h3>
        <?php echo Yii::t('texts', 'HEADER_COMPLAINT_BY_CLIENT') ;?>
    </h3>
    <div id="complaint_client">
        <?php
        $this->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaintClient, 'user' => Constants::USER_CLIENT));
        ?>
    </div>
    <hr />
    <br />
    <h3>
        <?php echo Yii::t('texts', 'HEADER_COMPLAINT_BY_CARER') ;?>
    </h3>
    <div id="complaint_carer">
        <?php
        $this->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaintCarer, 'user' => Constants::USER_CARER));
        ?>
    </div>
</div>
<div id="hook" style="display:none">
    <div id="dialog">
    </div>
</div>