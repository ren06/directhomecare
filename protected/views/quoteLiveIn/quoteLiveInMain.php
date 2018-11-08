<?php
$startTime = $quoteLiveIn->start_time;
$endTime = $quoteLiveIn->end_time;

if (isset($startTime)) {

    $startTimeHour = Calendar::convert_Time_Hour($quoteLiveIn->start_time);
    $startTimeMinutes = Calendar::convert_Time_Minute($quoteLiveIn->start_time);
} else {
    $startTimeHour = '12';
    $startTimeMinutes = '00';
}
if (isset($endTime)) {
    $endTimeHour = Calendar::convert_Time_Hour($quoteLiveIn->end_time);
    $endTimeMinutes = Calendar::convert_Time_Minute($quoteLiveIn->end_time);
} else {
    $endTimeHour = '12';
    $endTimeMinutes = '00';
}

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'live-in-quote-form',
    'method' => 'get',
    'enableAjaxValidation' => false,
        ));

$err = $quoteLiveIn->errors;
$test = $quoteLiveIn->end_date_time;
$err = $quoteLiveIn->errors;
?>
<?php if ($quoteLiveIn->hasErrors()) { ?>
    <div class="flash-error">
        <?php echo $form->error($quoteLiveIn, 'start_date_time'); ?>
        <?php //echo $form->error($quoteLiveIn, 'start_time'); ?>
        <?php echo $form->error($quoteLiveIn, 'end_date_time'); ?>
        <?php //echo $form->error($quoteLiveIn, 'end_time'); ?>
    </div>
<?php } ?>

    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'LABEL_LIVE_IN_CARE_UPPER'); ?>
        </div>
    </div>
<div class="rc-module-inside">
    <table class="rc-quote-control">
        <tr>
            <td class="rc-quote-cellbutton"></td>
            <td class="rc-quote-cellday">
                <?php echo $form->labelEx($quoteLiveIn, 'start_date', array('for' => 'liveInStartDate')); ?>
            </td>
            <td class="rc-quote-celltime">
                <?php
                $today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
                $rule = BusinessRules::getNewBookingDelayLiveInDays();
                $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                    'theme' => 'green',
                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
                    'name' => 'start_date',
                    'value' => $quoteLiveIn->getStartDate(false),
                    'options' => array('dateFormat' => 'dd/mm/yy',
                        'firstDay' => '1', //Monday
                        'minDate' => $minDate,
                        'maxDate' => '+730',
                        'changeYear' => 'true',
                        'changeMonth' => 'true',
                        'onSelect' => "js: function(dateText, inst) { handleStartDateSelect(dateText); }"
                    ),
                    'htmlOptions' => array('id' => 'liveInStartDate', 'class' => 'rc-field-medium'),));
                ?>
            </td>
            <td class="rc-quote-celltime">
                <?php
                echo CHtml::dropDownList('StartHour', $startTimeHour, Calendar::getDropDownHours($quoteLiveIn->type), array('class' => 'rc-drop', 'id' => 'start_hour'));
                //echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('StartMinute', $startTimeMinutes, Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute'));
                ?>
            </td>
        </tr>
        <tr>
            <td class="rc-quote-cellbutton">
                <?php echo CHtml::radioButton("Recurring", ($quoteLiveIn->recurring ? false : true), array('class' => 'toggleRadioButton', 'name' => 'radioNotRecurring', 'id' => 'radioNotRecurring', 'value' => false, 'uncheckValue' => null)); ?>
            </td>
            <td class="rc-quote-cellday">
                <?php
                $endDateClass = $quoteLiveIn->hasErrors('end_date_time') ? CHtml::$errorCss : '';

                echo CHtml::label(Yii::t('texts', 'LABEL_END_DATE'), 'radioNotRecurring', array('class' => $endDateClass));
                ?>
                <span class="required greyable">*</span>
            </td>
            <td class="rc-quote-celltime greyable greyableField">
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                    'theme' => 'green',
                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
                    'name' => 'end_date',
                    'value' => $quoteLiveIn->getEndDate(false),
                    'options' => array('dateFormat' => 'dd/mm/yy',
                        'firstDay' => '1',
                        'minDate' => Calendar::addDays(Calendar::today(Calendar::FORMAT_DISPLAYDATE), BusinessRules::getNewBookingDelayLiveInDays() + BusinessRules::getNewBookingLiveInMinimumDurationInDays(), Calendar::FORMAT_DISPLAYDATE),
                        'maxDate' => '+731', 'changeYear' => 'true', 'changeMonth' => 'true'),
                    'htmlOptions' => array('id' => 'liveInEndDate', 'class' => 'rc-field-medium ' . $endDateClass),));
                ?>
            </td>
            <td class="rc-quote-celltime greyable greyableDrop">
                <?php
                echo CHtml::dropDownList('EndHour', $endTimeHour, Calendar::getDropDownHours($quoteLiveIn->type), array('class' => 'rc-drop', 'id' => 'end_hour'));
                //echo Yii::t('texts', 'HOUR_SEPARATOR');
                echo CHtml::dropDownList('EndMinute', $endTimeMinutes, Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'end_minute'));
                ?>
            </td>
        </tr>
        <tr>
            <td class="rc-quote-cellbutton">
                <?php
                echo CHtml::radioButton("Recurring", $quoteLiveIn->recurring, array('class' => 'toggleRadioButton', 'name' => 'radioRecurring', 'id' => 'radioRecurring', 'value' => true, 'uncheckValue' => null));
                ?>
            </td>
            <td class="rc-quote-cellday" colspan="3">
                <span><?php echo CHtml::label(Yii::t('texts', 'LABEL_UNTIL_NOTICE'), 'radioRecurring'); ?></span>
            </td>
        </tr>
    </table>
    <div class="rc-container-button">
        <span class="buttons">
            <?php
            if ($quoteLiveIn->showResult) {
                //2nd time
                $text = Yii::t('texts', 'BUTTON_UPDATE_QUOTE');
            } else {
                //1st time
                $text = Yii::t('texts', 'BUTTON_GET_A_QUOTE');
            }
            echo CHtml::submitButton($text, array('class' => 'rc-button', 'submit' => array(ShowPriceAction::NAME)));
            ?>
        </span> 
        <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
    </div>
    <?php $this->endWidget(); ?>
    <?php
    if ($quoteLiveIn->showResult) {
//        $short = false;
//        
        ?>
        <div class="rc-container-conditions">
            <p>
                <?php // echo '<b>' . Yii::t('texts', 'LABEL_SERVICE') . '&#58;&#160;</b>' . Yii::t('texts', 'LABEL_LIVE_IN_CARE');  ?>
            </p>
            <?php
//                $slots = $quoteLiveIn->getLiveInMissionSlots();
//                if ($short == false) {
//                    $maxSlots = 1;
//                } else {
//                    $maxSlots = 1;
//                }
//                $i = 0;
//
//                foreach ($slots as $slot) {
//                    if ($i == $maxSlots) {
//                        break;
//                    }
//                    $lastSunday = Calendar::getLastSunday(Calendar::convert_DBDateTime_DBDate($slot->startDateTime));
//                    $toBePaidOn = date('Y-m-d', strtotime('-7 days', strtotime($lastSunday)));
//                    
            ?>
            <?php
//                    if (count($slots) > 1) {
//                        echo '<p><b>' . Yii::t('texts', 'LABEL_FIRST_2_WEEKS') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THE_SCHEDULE_IS_DONE_2_WEEKS')) . '</p>';
//                    }
//                    
            ?>
            <p>
                <?php // echo Calendar::convert_DBDateTime_DisplayDateTimeText($slot->startDateTime) . '&#160;&#160;&#160;&#160;' . Yii::t('texts', 'LABEL_UNTIL') . '&#160;&#160;&#160;&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($slot->endDateTime); ?>
            </p>
            <p>
                <?php
//                        echo $slot->duration . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::LIVE_IN_DAILY_PRICE);
//                        echo '&#58;&#160;<b>' . $slot->toPay->text . '</b>';
//                        
                ?>
            </p>
            <?php
//                    if ($i == $maxSlots - 1) {
//                        $endDateTime = $quoteLiveIn->end_date_time;
//                        if ($quoteLiveIn->recurring) {
//                            echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL_YOU_STOP') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
//                        } else {
//                            if (count($slots) > 1) {
//                                echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL') . Calendar::convert_DBDateTime_DisplayDateText($endDateTime) . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
//                            }
//                        }
//                    }
//                    $i++;
//                }
//                
            ?>
            <?php
            if ($quoteLiveIn->showResult) {
                $this->renderPartial('/quoteLiveIn/quoteLiveInResultMain', array('quoteLiveIn' => $quoteLiveIn));
            }
            ?>
        </div>
        <div class="rc-container-button">
            <span class="buttons">
                <?php
                if ($quoteLiveIn->showResult) {
                    echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS'), array('class' => 'rc-button', 'submit' => array('quote', 'navigation' => 'next')));
                }
                ?>
            </span>        
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        </div>
        <?php
    }
    ?>
</div>
<script type="text/javascript">

    function handleStartDateSelect(dateText) {
        //see _criteria
        handleEndDateDefaultLogic('liveIn', dateText);
    }

    function handleNotRecurring() {

        $(".greyable.required").css("visibility", "visible");
    }

    function handleRecurring() {

        $(".greyable.required").css("visibility", "hidden")
        $(".greyable.rc-error").hide();
    }

    $(document).ready(function()
    {
        if ($("#radioNotRecurring").is(":checked")) {
            handleNotRecurring();
        }
        else {
            handleRecurring();
        }

        $("#radioNotRecurring").click(function() { //end date            
            if ($(this).is(":checked")) {
                handleNotRecurring();
            }
        });

        $("#radioRecurring").click(function() {  //until further notice           
            if ($(this).is(":checked")) {
                handleRecurring();
            }
        });

        $("#end_date").click(function() { //end date            

            $("#radioNotRecurring").attr('checked', true);
            handleNotRecurring();

        });

        $(".greyableDrop :input").click(function() { //end date            

            $("#radioNotRecurring").attr('checked', true);
            handleNotRecurring();

        });
    });

</script>