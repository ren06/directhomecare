<?php $this->pageTitle = 'Abort Service'; //$this->pageTitle = Yii::t('texts', 'CLIENT_MODIFY_SERVICE_TITLE')       ?>
<h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>
<div class="rc-container-60">
    <br />
    <?php
    echo CHtml::link(Yii::t('texts', 'BACK_TO_SERVICES'), $this->createAbsoluteUrl('index'), array('class' => 'rc-linkbutton'));
    ?>
    <h3><?php echo 'Abort Service'; // echo Yii::t('texts', 'CLIENT_MODIFY_SERVICE_HEADER1');       ?><?php echo $request->id; ?></h3>

    <?php
    $this->renderPartial('_servicesTable', array(
        'dataProvider' => $dataProvider, 'buttonsColumnVisible' => $buttonsColumnVisible)
    );  
    ?>

    <h3><?php //echo Yii::t('texts', 'CLIENT_MODIFY_SERVICE_HEADER4');       ?></h3>

    <?php
    if ($request->isAbortable()) {
        ?>

        <p>
        <h3>Please select the date you want to abort the service. Since the carer must be noticed you can only abort the service 2 days from now</h3>
    </p>

    <?php
    $minDate = $request->getMinimumAbortDate();
    ?>

    <table>
        <tr>
            <td class="rc-cell1"><?php echo CHtml::label('End date', 'end_date') ?></td>
            <td class="rc-cell2"><?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array('id' => 'abortDate', 'name' => 'end_date', 'value' => $minDate,
        'options' => array('dateFormat' => 'dd/mm/yy', 'firstDay' => '1', 'minDate' => $minDate, 'maxDate' => $request->getEndDate(), 'changeYear' => 'true', 'changeMonth' => 'true'),
        'htmlOptions' => array('class' => 'rc-field-medium'),));
    ?></td>
            <td class="rc-cell3"><div style="display:none" id="wrongDate" class="rc-error">Please enter a date between today and the date the service finishes</div></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo CHtml::label('End time', 'end_date') ?></td>
            <td class="rc-cell2"><?php echo CHtml::textField('time', $request->getStartTime(), array('disabled' => 'disabled', 'class' => 'rc-field-medium')); ?></td>
        </tr>    
    </table>

    <?php
    echo CHtml::button('Abort', array('onClick' => "js:showDialog()", 'class' => 'rc-linkbutton', 'title' => 'Click to cancel your shift.'));
    ?>

    <?php
} else {
    ?>    
    <p>
    <h3>Sorry the service is ending in 2 days it's not possible to abort it anymore</h3>
    </p>
    <?php
}
?>


<div id="hook" style="display:none">
    <div id="dialog"></div>
</div>


</div>

<script type="text/javascript">

    function showDialog(){
    
        var url = '<?php echo CHtml::normalizeUrl(array("clientManageBookings/abortServiceDialog")) ?>';
         
        $.ajax({
            success: function(html){

                if(html == '\nerror'){
                    $('#wrongDate').show();
                }
                else{
                    $('#dialog').html(html);
                    $('#wrongDate').hide();
                }
            },
            context: this,
            type: 'post',
            url: url,
            data: {id: <?php echo $request->id ?>, type: '<?php echo get_class($request) ?>', abortDate: $('#abortDate').val()},
            cache: false, dataType: 'html'
        });
    
    }
    
    function dialogYes(buttonId, action, missionId){
    
        if(action == "abortService"){
        
            var url = '<?php echo CHtml::normalizeUrl(array("clientManageBookings/abortService")) ?>';
        }
        $('#' + buttonId).attr("disabled", true); 
        $.ajax({
            beforeSend:function(){
            },
            success: function(html){

                if(html == 'errorDate'){
                    alert('wrong date');
                }
                else{
                    //$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');
                    window.location.replace('<?php echo CHtml::normalizeUrl(array("clientManageBookings/index")) ?>');
                }
            },
            context: this,
            type: 'post',
            url: url,
            data: {id: missionId, abortDate: $('#abortDate').val()},
            cache: false, dataType: 'html'
        });
    
    }
</script>