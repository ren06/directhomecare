
function dialogYes(buttonId, url, grid, missionId) {

    $.ajax({
        success: function(html) {

            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
            //alert(grid);            
            $(grid).replaceWith(html);
        },
        context: this,
        type: 'post',
        url: url,
        data: {id: missionId},
        cache: false, dataType: 'html'
    });
}



///////////////////

//chooseMission
//function dialogYes(buttonId, actionURL, missionId) {
//
//    if (actionURL == "cancelApplied") {
//
//        var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelAppliedChooseMission")) ?>';
//    }
//    $('#' + buttonId).attr("disabled", true);
//
//    $.ajax({
//        success: function(html) {
//
//            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
//            $('#<?php echo Tables::AVAILABLE_MISSIONS_GRID ?>').html(html);
//        },
//        context: this,
//        type: 'post',
//        url: url,
//        data: {id: missionId},
//        cache: false, dataType: 'html'
//    });
//
//}
//
////my mission
//function dialogYes(buttonId, action, missionId, missionType) {
//
//    if (action == "cancelSelected") {
//
//        var grid = '#<?php echo Tables::SELECTED_MISSIONS_GRID ?>';
//        var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelSelected")) ?>';
//    }
//    else if (action == "confirmSelected") {
//        var grid = '#assignedAndSelectedMissions';
//        var url = '<?php echo CHtml::normalizeUrl(array("carer/confirmSelected")) ?>';
//    }
//    else if (action == "cancelApplied") {
//        var grid = '#<?php echo Tables::AWAITING_MISSIONS_GRID ?>';
//        var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelAppliedBookedMission")) ?>';
//    }
//    else if (action == "cancelAssigned") {
//        var grid = '#<?php echo Tables::ASSIGNED_MISSIONS_GRID ?>';
//        var url = '<?php echo CHtml::normalizeUrl(array("carer/cancelAssigned")) ?>';
//    }
//    $('#' + buttonId).attr("disabled", true);
//    $.ajax({
//        success: function(html) {
//
//            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
//            //alert(html);
//            $(grid).replaceWith(html);
//        },
//        context: this,
//        type: 'post',
//        url: url,
//        data: {id: missionId, type: missionType},
//        cache: false, dataType: 'html'
//    });
//}
//
////carer visits
//function dialogYes(buttonId, action, missionId) {
//
//    if (action == "cancelMission") {
//
//        var grid = '#<?php echo Tables::BOOKED_SERVICES_GRID ?>';
//        var url = '<?php echo CHtml::normalizeUrl(array("clientManageBookings/cancelService")) ?>';
//    }
//    $('#' + buttonId).attr("disabled", true);
//    $.ajax({
//        success: function(html) {
//
//            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
//            $(grid).parent().html(html);
//        },
//        context: this,
//        type: 'post',
//        url: url,
//        data: {id: missionId},
//        cache: false, dataType: 'html'
//    });
//
//}

    