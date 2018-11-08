<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php if (Yii::app()->params['test']['showSpec'] == true) { ?>
    <b>Shifts being verified Table</b><br />
    Display shifts if: shift is COMPLETED AND NOT_CREDITED AND ACTIVE<br />
    <b>Status</b><br />
    if shift is OPENED_COMPLAINT: Complaint-status<br />
    elseif shift is SOLVED_COMPLAINT: "3 days" + Complaint-status<br />
    else (no complaint ever opened): "3 days"<br />
    <b>Buttons</b><br />
    "Details" | "Complaint"<br />
<?php } ?>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_MISSIONS_BEING_VERIFIED');
$this->pageSubTitle = 'Please allow some time to check with the client if eveything went right';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myMissionsMenu', array('selectedMenu' => 'verifyingMissions')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        if (sizeof($finishedMissions->getData()) > 0) {
            $this->renderPartial('_missionsTable', array('id' => 'table', 'dataProvider' => $finishedMissions, 'columns' => Tables::getCarerCompletedMissionsColumnConfig($scenario)));
        } else {
            echo '<p><b>' . Yii::t('texts', 'TABLES_NO_MISSIONS') . '</b></p>';
        }
        ?>
    </div>
</div>