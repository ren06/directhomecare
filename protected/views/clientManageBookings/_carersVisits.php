<div id="bookedServices"> 
    <?php

    if (count($dataProvider->getData()) == 0) {

        echo '<b>' . Yii::t('texts', 'TABLES_NO_VISITS') . '</b>';
    } else {
        $this->renderPartial('_carerVisitsTable', array('dataProvider' => $dataProvider, 'buttonsColumnVisible' => true, 'scenario' => $scenario)
        );
    }
    ?>
</div>

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/dialogYesTable.js');
?>   

<script type="text/javascript">

//    function dialogYes(buttonId, action, missionId){
//    
//        if(action == "cancelMission"){
//        
//            var grid = '#<?php echo Tables::BOOKED_SERVICES_GRID ?>';
//            var url = '<?php echo CHtml::normalizeUrl(array("clientManageBookings/cancelService")) ?>';
//        }
//        $('#' + buttonId).attr("disabled", true);
//        $.ajax({
//            success: function(html){
//
//                $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
//                $(grid).parent().html(html);
//            },
//            context: this,
//            type: 'post',
//            url: url,
//            data: {id: missionId},
//            cache: false, dataType: 'html'
//        });
//    
//    }
</script>