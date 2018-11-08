
<h2>Shift <?php echo $mission->id ?></h2>

<?php
$url = Yii::app()->createUrl('/admin/missionAdmin/update/id/' . $mission->id);
echo CHtml::link('Edit', $url, array());
echo ' | ';
if ($mission->isActive()) {

    $url = Yii::app()->createUrl('/admin/missionAdmin/cancelForClient/id/' . $mission->id);
    echo CHtml::link('Cancel for Client (voucher)', $url, array());
    echo ' | ';
    $carer = $mission->getAssignedCarer();
    if (isset($carer)) {
        $url = Yii::app()->createUrl('/admin/missionAdmin/cancelForCarer/id/' . $mission->id);
        echo CHtml::link('Cancel for Carer ' . $carer->fullName . ' (refund - results in a cancel by admin)', $url, array());
    } else {
        echo 'No carer assigned';
    }
} else {
    echo '<b><font color="#C36">Shift is ' . $mission->getStatusLabel() . '</font></b>';
}
?>
<br><br>
<?php
echo $mission->displayMissionAdmin();

echo '<br /><b>Original prices</b><br />';
echo 'Paid by Client: ' . $mission->getOriginalTotalPrice(Constants::USER_CLIENT)->text . '<br />';
echo 'Pay for Carer: ' . $mission->getOriginalTotalPrice(Constants::USER_CARER)->text . '<br />';

$slots = $mission->getMissionSlotsAborted();

if (count($slots) > 0) {

    echo '<br /><b>New prices</b><br />';
    echo 'Paid by Client: ' . $mission->getTotalPrice(Constants::USER_CLIENT)->text . '<br />';
    echo 'Pay for Carer: ' . $mission->getTotalPrice(Constants::USER_CARER)->text . '<br />';
}

$missionStartDate = Calendar::convert_DBDateTime_DisplayDate($mission->start_date_time);
$missionEndDate = Calendar::convert_DBDateTime_DisplayDate($mission->end_date_time);

$startTime = $missionSlotAborted->start_time;
$endTime = $missionSlotAborted->end_time;

$slotStartDate = Calendar::convert_DBDateTime_DisplayDate($missionSlotAborted->start_date_time);
$slotEndDate = Calendar::convert_DBDateTime_DisplayDate($missionSlotAborted->end_date_time);

if (isset($startTime)) {

    $startTimeHour = Calendar::convert_Time_Hour($missionSlotAborted->start_time);
    $startTimeMinutes = Calendar::convert_Time_Minute($missionSlotAborted->start_time);
} else {
    $startTimeHour = '12';
    $startTimeMinutes = '00';
}

if (isset($endTime)) {

    $endTimeHour = Calendar::convert_Time_Hour($missionSlotAborted->end_time);
    $endTimeMinutes = Calendar::convert_Time_Minute($missionSlotAborted->end_time);
} else {
    $endTimeHour = '12';
    $endTimeMinutes = '00';
}

if ($mission->type == Constants::HOURLY) {

    //if hourly mission the start and end date are always the same
    if ($slotStartDate == '' && $slotEndDate == '') {

        $slotStartDate = Calendar::convert_DBDateTime_DisplayDate($mission->start_date_time);
        $slotEndDate = Calendar::convert_DBDateTime_DisplayDate($mission->end_date_time);
    }
}
?>
<br />
<h3>Aborted time slots</h3>


<?php
foreach ($slots as $slot) {

    $id = $slot->id;

    echo "<div id='$id'>";
    echo $slot->displayAdmin();

    echo ' ';

    $url = $this->createUrl('missionAdmin/viewMission' . '/missionId/' . $mission->id);
    echo CHtml::ajaxLink("Delete", Yii::app()->createUrl('admin/missionAdmin/deleteMissionSlotAborted'), array(
        'type' => 'POST',
        'beforeSend' => "function(request){
           confirm('Are you sure?');}",
        'success' => "function(data){                                          
                       $('body').load('$url');}",
        'data' => array('slotId' => $id)
            ), array(//htmlOptions
        'href' => Yii::app()->createUrl('admin/missionAdmin/deleteMissionSlotAborted'),
            //'class' => $class
            )
    );
    echo '<br />';
    echo 'Client price: ' . $slot->getPriceText(Constants::USER_CLIENT) . '<br />';
    echo 'Carer price: ' . $slot->getPriceText(Constants::USER_CARER) . '<br />';
    echo '</div>';
}
?>
<br />

<div>
    <?php
    if ($mission->isActive()) {

        echo CHtml::button('New shift aborted slot', array('onClick' => '$("#newMissionAbortedSlot").show();'));
    } else {
        echo '<b><font color="#C36">Cannot create an aborted slot as the shift is ' . $mission->getStatusLabel() . '</font></b>';
    }
    ?>
</div>
<br />

<?php
$style = 'display:none';

if (sizeof($missionSlotAborted->errors) > 0 || ($paymentResult != 'Payment successful' && $paymentResult != '')) {

    $style = 'display:block';
}
?>

<div id="newMissionAbortedSlot" style="<?php echo $style ?>">
    <h3>Create aborted slot</h3>
    <?php echo $paymentResult ?>
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mission-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <p class="rc-note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($missionSlotAborted); ?>                

        <?php echo CHtml::hiddenField('missionId', $mission->id); ?>

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'start_date'); ?></td>
            <td class="rc-cell2"><?php $this->widget('zii.widgets.jui.CJuiDatePicker', array('name' => 'start_date', 'value' => $slotStartDate, 'options' => array('dateFormat' => 'dd/mm/yy', 'firstDay' => '1', 'minDate' => $missionStartDate, 'maxDate' => $missionEndDate, 'changeYear' => 'true', 'changeMonth' => 'true'), 'htmlOptions' => array('size' => '10'),)); ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'start_date', array('class' => 'rc-error')); ?></td>
        </div>

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'start_time'); ?></td>
            <td class="rc-cell2"><?php echo CHtml::dropDownList('StartHour', $startTimeHour, Calendar::getDropDownHours(Calendar::DROPDOWNHOURS_ALL), array('class' => 'rc-drop', 'id' => 'start_hour')); ?><?php
                echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('StartMinute', $startTimeMinutes, Calendar::getDropDownMinutes(true), array('class' => 'rc-drop', 'id' => 'start_minute'));
                ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'start_time', array('class' => 'rc-error')); ?></td>
        </div>

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'end_date'); ?></td>
            <td class="rc-cell2"><?php $this->widget('zii.widgets.jui.CJuiDatePicker', array('name' => 'end_date', 'value' => $slotEndDate, 'options' => array('dateFormat' => 'dd/mm/yy', 'firstDay' => '1', 'minDate' => $missionStartDate, 'maxDate' => $missionEndDate, 'changeYear' => 'true', 'changeMonth' => 'true'), 'htmlOptions' => array('size' => '10'),)); ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'end_date', array('class' => 'rc-error')); ?></td>
        </div>

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'end_time'); ?></td>
            <td class="rc-cell2"><?php echo CHtml::dropDownList('EndHour', $endTimeHour, Calendar::getDropDownHours(Calendar::DROPDOWNHOURS_ALL), array('class' => 'rc-drop', 'id' => 'end_hour')); ?><?php
                echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('EndMinute', $endTimeMinutes, Calendar::getDropDownMinutes(true), array('class' => 'rc-drop', 'id' => 'end_minute'));
                ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'end_time', array('class' => 'rc-error')); ?></td>
        </div>    

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'aborted_by'); ?></td>
            <td class="rc-cell2"><?php
                echo CHtml::radioButtonList('aborted_by', $missionSlotAborted->aborted_by, $missionSlotAborted::getAbortedByOptions(), array('labelOptions' => array('style' => 'display:inline'), 'separator' => Yii::app()->params['radioButtonSeparator'],
                    'onChange' => CHtml::ajax(array('type' => 'POST', 'url' => array("missionAdmin/updateSlotType"),
                        'update' => '#drop_down_type'))));
                ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'reported_by', array('class' => 'rc-error')); ?></td>
        </div>  

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'type'); ?></td>
            <?php
            if (isset($missionSlotAborted->aborted_by)) {

                if ($missionSlotAborted->aborted_by == MissionSlotAborted::ABORTED_BY_CLIENT) {
                    $options = MissionSlotAborted::getTypeClientOptions();
                } else {
                    $options = MissionSlotAborted::getTypeClientOptions();
                }
            } else {
                $options = array();
            }
            ?>
            <td class="rc-cell2"><?php echo CHtml::dropDownList('type', $missionSlotAborted->type, $options, array('id' => 'drop_down_type')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'type', array('class' => 'rc-error')); ?></td>
        </div>   

        <div class="row">
            <td class="rc-cell1-quote"><?php echo $form->labelEx($missionSlotAborted, 'reported_by'); ?></td>
            <td class="rc-cell2"><?php echo CHtml::dropDownList('reported_by', $missionSlotAborted->type, $missionSlotAborted::getReportedByOptions(), array('class' => 'rc-drop', 'id' => 'end_hour')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($missionSlotAborted, 'reported_by', array('class' => 'rc-error')); ?></td>
        </div>   

        <div class="row buttons">
            <?php
            echo CHtml::submitButton('Create', array('submit' => $this->createUrl('missionAdmin/createMissionSlotAborted')));
            echo CHtml::button('Cancel', array('submit' => $this->createUrl('missionAdmin/viewMission' . '/missionId/' . $mission->id)));
            ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->

</div>