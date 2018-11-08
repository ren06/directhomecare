<?php
/* @var $form CActiveForm */

if (isset($booking->end_date_time)) {

    $endDate = Calendar::convert_DBDateTime_DisplayDate($booking->end_date_time);
    $endTime = Calendar::convert_DBDateTime_Time($booking->end_date_time);

    $endTimeHour = Calendar::convert_Time_Hour($endTime);
    $endTimeMinutes = Calendar::convert_Time_Minute($endTime);
} else {

    $endDate = '';
    $endTimeHour = '12';
    $endTimeMinutes = '00';
}

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'autoOpen' => true,
        'title' => Yii::t('texts', 'HEADER_CHANGE_END_DATE'),
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '480',
    // 'height' => '260',
    ),
));
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'booking-gap-form',
        'enableAjaxValidation' => true,
        //'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false
        ),
        'focus' => '#start_hour', //bug if focus on CJuiDialog
        'action' => 'createHolidayGap'
    ));

    echo CHtml::hiddenField('bookingId', $booking->id); //used to pass booking id 
    ?>
    <table>
        <tr>
            <td class="rc-quote-cellbutton"></td>
            <td class="rc-quote-cellday" colspan="3">
                <?php
                echo CHtml::link(Yii::t('texts', 'LABEL_CLICK_FOR_EARLIEST_END_DATE'), '', array('class' => 'rc-link', 'id' => 'earliestEndDate'));
                ?>
            </td>
        </tr>
        <tr>
            <td class="rc-quote-cellbutton">
                <?php
                echo CHtml::radioButton('setEndDate', !$booking->recurring, array('class' => 'toggleRadioButton', 'id' => 'setEndDate')) . '&#160;';
                ?>  
            </td>
            <td class="rc-quote-cellday">
                <?php
                echo CHtml::label(Yii::t('texts', 'LABEL_END_DATE'), 'setEndDate');
                ?>
                <span class="required" style="visibility:hidden">*</span>
            </td>
            <td class="rc-quote-celltime">
                <?php
                $minDate = Calendar::convert_DBDateTime_DisplayDate(Booking::getLastMission($booking->id)->end_date_time);

                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                    'theme' => 'green',
                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
                    'name' => 'end_date',
                    'value' => $endDate,
                    'options' => array('dateFormat' => 'dd/mm/yy', 'firstDay' => '1', 'minDate' => $minDate, 'maxDate' => '+731', 'changeYear' => 'true', 'changeMonth' => 'true'), 'htmlOptions' => array('class' => 'rc-field-medium'),));

                echo ' ';
                ?>
            </td>
            <?php
            if ($booking->type == Constants::LIVE_IN) {
                ?>
                <td class="rc-quote-cellbutton">
                    <?php
                    echo CHtml::dropDownList('EndHour', $endTimeHour, Calendar::getDropDownHours($booking->type), array('class' => 'rc-drop', 'id' => 'end_hour'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::dropDownList('EndMinute', $endTimeMinutes, Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'end_minute'));
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td class="rc-quote-cellbutton">
                <?php
                echo CHtml::radioButton('untilFurther', $booking->recurring, array('class' => 'toggleRadioButton', 'id' => 'untilFurther')) . '&#160;';
                ?>
            </td>
            <td class="rc-quote-cellday" colspan="3">
                <span><?php echo CHtml::label(Yii::t('texts', Yii::t('texts', 'LABEL_UNTIL_NOTICE')), 'untilFurther'); ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="rc-cell-error">
                <span id="errorMessage" class="rc-error"></span>
            </td>
        </tr>
    </table>
    <div class="rc-container-button">
        <div class="buttons">
            <?php
            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_VALIDATE'), CHtml::normalizeUrl(array('changeEndDate')), array(
                'beforeSend' => 'function() {}',
                'success' => 'function(data, status, xhr) {
                
                //clear any existing errors
                $("#errorMessage").html("");
               
                if(data === "abort"){
                                 
                    xhr.abort();
                }
                else{
                    
                    if (data != "") {
                        
                        $("#errorMessage").html(data);
                        //hide ajax loader
                        $(".loading").hide();
                        //show again buttons
                        $("#OK").closest(".rc-container-button").find(".buttons").show();
                    }
                    else {
                         window.location.href="' . CHtml::normalizeUrl(array('myBookings')) . '"; 
                    }   
                }
            }',
                'error' => 'function(data) {}',
                    ), array('class' => 'rc-button', 'id' => 'OK')
            );

            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_CLOSE'), CHtml::normalizeUrl(array('changeEndDateCancel')), array(
                'beforeSend' => 'function() {}',
                'success' => 'function(data, status, xhr) {
                
                    $("div.ui-dialog:visible").find("div.ui-dialog-content").dialog("close");

            }',
                'error' => 'function(data) {}',
                    ), array('class' => 'rc-button', 'id' => 'Cancel')
            );
            ?>
        </div>
        <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form --> 

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php
//used for the stop future payment link
$earliestEndDate = $booking->getEarliestEndDate();

$endDate = Calendar::convert_DBDateTime_DisplayDate($earliestEndDate);
$endTime = Calendar::convert_DBDateTime_DisplayTime($earliestEndDate);

$endTimeHour = Calendar::convert_Time_Hour($endTime);
$endTimeMinutes = Calendar::convert_Time_Minute($endTime);
?>

<script type="text/javascript">

    $(document).ready(function() {

        //STATUS

        if ($("#untilFurther").is(":checked")) {
            $(".required").css("visibility", "hidden")
        }
        else if ($("#setEndDate").is(":checked")) {
            $(".required").css("visibility", "visible")
        }

        //CLICKS

        $("#untilFurther").click(function() {
            $(':radio').attr('checked', false);
            $(this).attr('checked', true);
            $("#errorMessage").html("");
            $(".required").css("visibility", "hidden")
        });

        $("#setEndDate").click(function() {
            $(':radio').attr('checked', false);
            $(this).attr('checked', true);
            $(".required").css("visibility", "visible")
        });

        $("#end_date").click(function() {
            $(':radio').attr('checked', false);
            $("#setEndDate").attr('checked', true);
            $(".required").css("visibility", "visible")
        });

        $("#earliestEndDate").click(function() {
            $(':radio').attr('checked', false);
            $("#setEndDate").attr('checked', true);
            $(".required").css("visibility", "visible")
            $("#end_date").val('<?php echo $endDate ?>');
            $("#end_hour").val('<?php echo $endTimeHour ?>');
            $("#end_minute").val('<?php echo $endTimeMinutes ?>');
        });
    });

</script>