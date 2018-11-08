<?php
/* @var $holidayGap BookingGap */
/* @var $form CActiveForm */
?>

<?php
$startTime = $holidayGap->start_time;
$endTime = $holidayGap->end_time;

if (isset($startTime)) {

    $startTimeHour = Calendar::convert_Time_Hour($holidayGap->start_time);
    $startTimeMinutes = Calendar::convert_Time_Minute($holidayGap->start_time);
} else {
    $startTimeHour = '12';
    $startTimeMinutes = '00';
}

if (isset($endTime)) {

    $endTimeHour = Calendar::convert_Time_Hour($holidayGap->end_time);
    $endTimeMinutes = Calendar::convert_Time_Minute($holidayGap->end_time);
} else {
    $endTimeHour = '12';
    $endTimeMinutes = '00';
}
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'autoOpen' => true,
        'title' => 'Create a holiday gap',
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '600',
        //'height' => '320',
    ),
));
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'booking-gap-form',
        'enableAjaxValidation' => true,        
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false
        ),
        'focus' => '#start_hour', //bug if focus on CJuiDialog
        'action' => 'createHolidayGap'
    ));
    ?>

    <?php
    echo CHtml::hiddenField('bookingId', $booking->id); //used to pass booking id 
    ?>

    <table>
        <tr>
            <?php echo $form->textField($holidayGap, 'start_date_time', array('style' => 'display:none')); //workaround for bug with date picker?>
            <td class="rc-cell1-quote"><?php echo $form->labelEx($holidayGap, 'start_date'); ?></td>       
            <td class="rc-cell2"><?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                    'theme' => 'green',
                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
                    'name' => 'start_date',
                    'value' => $holidayGap->getStartDate(false),
                    'options' => array('dateFormat' => 'dd/mm/yy',
                        'firstDay' => '1',
                        'minDate' => Calendar::addDays(Calendar::today(Calendar::FORMAT_DISPLAYDATE), Yii::app()->params['businessRules']['newBookingDelayLiveIn'], Calendar::FORMAT_DISPLAYDATE),
                        'maxDate' => '+730',
                        'changeYear' => 'true', 'changeMonth' => 'true'),
                    'htmlOptions' => array('class' => 'rc-field-medium'),));
                ?>
            </td>                     
            <td class="rc-cell3"><?php echo $form->error($holidayGap, 'start_date', array('class' => 'rc-error')); ?></td>       
        </tr>
        <tr>
            <td class="rc-cell1-quote"><?php echo $form->labelEx($holidayGap, 'start_time'); ?></td>    
            <td class="rc-cell2">
                <?php
                echo CHtml::dropDownList('StartHour', $startTimeHour, Calendar::getDropDownHours($booking->type), array('class' => 'rc-drop', 'id' => 'start_hour'));
                echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('StartMinute', $startTimeMinutes, Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute'));
                ?>       
            </td>
            <td class="rc-cell3"><?php echo $form->error($holidayGap, 'start_time', array('class' => 'rc-error')); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1-quote"><?php echo $form->labelEx($holidayGap, 'end_date'); ?></td>
            <td class="rc-cell2"><?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                    'theme' => 'green',
                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
                    'name' => 'end_date',
                    'value' => $holidayGap->getEndDate(false),
                    'options' => array('dateFormat' => 'dd/mm/yy', 'firstDay' => '1',
                        'minDate' => Calendar::addDays(Calendar::today(Calendar::FORMAT_DISPLAYDATE), (Yii::app()->params['businessRules']['newBookingDelayLiveIn'] + Yii::app()->params['businessRules']['newBookingMinimumNumberDaysLiveIn']), Calendar::FORMAT_DISPLAYDATE),                                            
                        'maxDate' => '+731',
                        'changeYear' => 'true',
                        'changeMonth' => 'true'), 'htmlOptions' => array('class' => 'rc-field-medium'),));
                ?>
            </td>

            <td class="rc-cell3"><?php echo $form->error($holidayGap, 'end_date', array('class' => 'rc-error')); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1-quote"><?php echo $form->labelEx($holidayGap, 'end_time'); ?></td>
            <td class="rc-cell2">
                <?php
                echo CHtml::dropDownList('EndHour', $endTimeHour, Calendar::getDropDownHours($booking->type), array('class' => 'rc-drop', 'id' => 'end_hour'));
                echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('EndMinute', $endTimeMinutes, Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'end_minute'));
                ?>
            </td>
            <td class="rc-cell3"><?php echo $form->error($holidayGap, 'end_time', array('class' => 'rc-error')); ?></td>
        </tr>
    </table>
    <div class="rc-container-button">
        <?php
        echo CHtml::ajaxSubmitButton('Create', CHtml::normalizeUrl(array('createHolidayGap')), array(
            'beforeSend' => 'function() {}',
            'success' => 'function(data) {
                
                //clear any existing errors
                $("[id*=_em_]").hide();
              
                if (data.indexOf("{")==0) {
                    var json = $.parseJSON(data);
                    $.each(json, function(key, value) {
                        $("#" + key).addClass("clsError");
                        $("#" + key + "_em_").show().html(value.toString());
                        $("label[for=" + key + "]").addClass("clsError");
                    });
                }
                else {
                    $("body").load("' . CHtml::normalizeUrl(array('myBookings')) . '"); 
                }              
            }',
            'error' => 'function(data) {}',
                ), array('class' => 'rc-button-small', 'id' => 'save')
        );

        echo ' ';
        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small', 'id' => 'closeDialog', 'name' => 'closeDialogName'
            , 'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');"));
        ?>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form --> 

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
