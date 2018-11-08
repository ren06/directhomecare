<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php if (Yii::app()->params['test']['showSpec'] == true) { ?>
    <b>Opened complaint Table</b><br />
    Display shifts if: OPENED_COMPLAINT<br />
    <b>Status</b><br />
    only Complaint-status<br />
    <b>Buttons</b><br />
    "Details" | "Complaint"<br />
<?php } ?>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_OPENED_COMPLAINTS');
$this->pageSubTitle = 'Let\'s sort this out';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myMissionsMenu', array('selectedMenu' => 'complaintMissions')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_THERE_ARE_OPENED_COMPLAINTS_FOR_THE_MISSIONS_BELLOW'); ?></p>
        <?php
        if (count($dataProvider->getData()) > 0) {
            $this->renderPartial('_missionsTable', array('id' => 'table', 'dataProvider' => $dataProvider, 'columns' => Tables::getCarerAssignedMissionsColumnConfig($scenario)));
        } else {
            echo '<p><b>' . Yii::t('texts', 'TABLES_NO_MISSIONS') . '</b></p>';
        }
        ?>
    </div>
</div>