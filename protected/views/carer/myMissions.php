<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php if (Yii::app()->params['test']['showSpec'] == true) { ?>
    <b>Confirmed Table</b><br />
    Display shifts if: end-user is CONFIRMED AND shift is NOT_STARTED OR STARTED<br />
    <b>Status</b><br />
    if shift is ACTIVE AND NOT_STARTED: "Shift not started"<br />
    elseif shift is ACTIVE AND STARTED: "Shift started" + Complaint-status<br />
    elseif shift is CANCELLED_BY_CLIENT: "Cancelled by client"<br />
    elseif shift is CANCELLED_BY_ADMIN: "Cancelled by administrator"<br />
    <b>Buttons</b><br />
    if shift is ACTIVE AND NOT_STARTED AND MORE_THAN_48H_BEFORE_START: "Details" | "Cancel shift"<br />
    if shift is ACTIVE AND NOT_STARTED: "Details" | "Cancel shift"<br />
    elseif shift is ACTIVE AND STARTED: "Details" | "Complain"<br />
    else: "Discard"<br />
    <br />
    <b>Selected Table</b><br />
    Display shifts if: end-user is SELECTED OR TOO_LATE_TO_CONFIRM AND shift is NOT_DISCARDED<br />
    <b>Status</b><br />
    if shift is ACTIVE AND end-user is SELECTED: "22 hours or Hurry up !"<br />
    elseif shift is ACTIVE AND NOT_DISCARDED AND end-user is TOO_LATE_TO_CONFIRM: "Too late to confirm"<br />
    elseif shift is CANCELLED_BY_CLIENT: "Cancelled by client"<br />
    elseif shift is CANCELLED_BY_ADMIN: "Cancelled by administrator"<br />
    <b>Buttons</b><br />
    if shift is ACTIVE AND end-user is SELECTED: "Details" | "Confirm" | "Cancel selection"<br />
    else: "Discard"<br />
    <br />
    <b>Awaiting Table</b><br />
    Display shifts if: end-user is APPLIED OR NOT_SELECTED<br />
    <b>Status</b><br />
    if shift is ACTIVE AND end-user is APPLIED: "Applied"<br />
    elseif shift is ACTIVE AND NOT_DISCARDED AND end-user is NOT_SELECTED: "Sorry you were not selected"<br />
    elseif shift is CANCELLED_BY_CLIENT: "Cancelled by client"<br />
    elseif shift is CANCELLED_BY_ADMIN: "Cancelled by administrator"<br />
    <b>Buttons</b><br />
    if shift is ACTIVE AND end-user is APPLIED: "Cancel application"<br />
    else: "Discard"<br />
<?php } ?>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_CURRENT_MISSIONS');
$this->pageSubTitle = 'Confirm and view your shits here';
?>

<?php
$awaitingCount = count($awaitingMissionsDataProvider->getData());
$assignedCount = count($assignedMissionsDataProvider->getData());
$selectedCount = count($selectedMissionsDataProvider->getData());
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myMissionsMenu', array('selectedMenu' => 'currentMissions')); ?>
    </div>
</div>

<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        $this->renderPartial('_missionsTables', array(
            'assignedMissionsDataProvider' => $assignedMissionsDataProvider,
            'selectedMissionsDataProvider' => $selectedMissionsDataProvider,
            'scenario' => $scenario));
        
        if ($awaitingCount > 0) {
            ?>
            <h3>Confirm that you want these shifts</h3>
            <div id='awaitingMissions'>
                <?php
                $this->renderPartial('_missionsTable', array('id' => Tables::AWAITING_MISSIONS_GRID, 'dataProvider' => $awaitingMissionsDataProvider, 'columns' => Tables::getCarerAwaitingMissionsColumnConfig()));
                ?>
            </div>
            <?php
        }

        if ($awaitingCount == 0 && $assignedCount == 0 && $selectedCount == 0) {
            echo '<p><b>' . Yii::t('texts', 'TABLES_NO_MISSIONS') . '</b></p>';
        }
        ?>
        <div id="hook" style="display:none">
            <div id="dialog">
            </div>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/dialogYesTable.js');
?>   

<script type="text/javascript">

    //    function confirmSelected(dialogId, missionId, missionType){
    //
    //        $.ajax({
    //            success: function(html){
    //                
    //               
    //                // $('#confirm-mission-dialog').dialog("destroy").remove();
    //
    //                //$(".ui-dialog:has(#confirm-mission-dialog)").empty().remove();
    //                
    //                //$('[class^=ui-]').unbind();
    //
    //                //$('#' + dialogId).dialog("destroy").remove();
    //                
    //                //$('#' + dialogId).dialog("close");
    //                $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
    //                $('#assignedAndSelectedMissions').html(html);
    //                
    //                //$.fn.yiiGridView.update('assignedMissionsGrid');
    //                          
    //            },
    //            context: this,
    //            type: 'post',
    //            url: '<?php //echo CHtml::normalizeUrl(array("carer/confirmSelected"))           ?>',
    //            data: {id: missionId, type: missionType},
    //            cache: false, dataType: 'html'
    //        });
    //        
    //        return false;
    //    };

//    function dialogYes(buttonId, action, missionId, missionType) {
//
//        if (action == "cancelSelected") {
//
//            var grid = '#<?php echo Tables::SELECTED_MISSIONS_GRID ?>';
//            var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelSelected")) ?>';
//        }
//        else if (action == "confirmSelected") {
//            var grid = '#assignedAndSelectedMissions';
//            var url = '<?php echo CHtml::normalizeUrl(array("carer/confirmSelected")) ?>';
//        }
//        else if (action == "cancelApplied") {
//            var grid = '#<?php echo Tables::AWAITING_MISSIONS_GRID ?>';
//            var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelAppliedBookedMission")) ?>';
//        }
//        else if (action == "cancelAssigned") {
//            var grid = '#<?php echo Tables::ASSIGNED_MISSIONS_GRID ?>';
//            var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelAssigned")) ?>';
//        }
//        $('#' + buttonId).attr("disabled", true);
//        $.ajax({
//            success: function(html) {
//
//                $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
//                //alert(html);
//                $(grid).replaceWith(html);
//            },
//            context: this,
//            type: 'post',
//            url: url,
//            data: {id: missionId, type: missionType},
//            cache: false, dataType: 'html'
//        });
//    }
</script>