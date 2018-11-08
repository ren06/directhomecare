<!-- for date picker -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation-datepicker/font-awesome.css"/>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation-datepicker/foundation-datepicker.css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation-datepicker/foundation-datepicker.js"></script>


<?php
$today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
$rule = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
$minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);

$date = $dayForm->date;

if ($dayForm->hasErrors()) {
    $style = "display:visible";
    $error = CHtml::error($dayForm, "[$index]date");
    $error .= CHtml::error($dayForm, "[$index]endHour");
} else {
    $style = "display:none";
    $error = '';
}
?>
<div class="dayFormContainer" id="dayFormContainer_<?php echo $index ?>">
    <div class="flash-error" id="flash-error-<?php echo $index ?>"style="<?php echo $style ?>;margin-top:0;">
        <?php echo $error; ?>
    </div>
    <div class="row">
        <div class="large-2 medium-2 small-6 columns">
            <?php $spanId = 'dateDay_' . $index; ?>
                <!-- <span id="<?php // hiden by RC echo $spanId;        ?>">
            <?php
            // if (isset($date) && $date != '') {
            //    echo Calendar::getDayOfWeekText($date, Calendar::FORMAT_DISPLAYDATE, false);
            // }
            ?> -->
            </span>
        </div>
        <div class="large-3 medium-3 small-6 columns">
            <input type="text" class ="toto" readonly  placeholder="Click to pick a date" name="<?php echo "HourlyQuoteDayForm[$index][date]"; ?>" value="<?php echo $date ?>" data-date="<?php echo $date ?>" />
        </div>
        <div class="large-2 medium-2 small-6 columns">
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$index]startHour", Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop change-hours no-validate', 'id' => 'start_hour_one_day'));
            ?>
        </div>
        <div class="large-2 medium-2 small-6 columns">
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$index]startMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop change-hours', 'id' => 'start_minute_one_day'));
            ?>
        </div>
        <div class="large-2 medium-2 small-6 columns">
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$index]endHour", Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop change-hours', 'id' => 'end_hour_one_day'));
            ?>
        </div>
        <div class="large-2 medium-2 small-6 columns">
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$index]endMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop change-hours', 'id' => 'end_minute_one_day'));
            ?>
        </div>
        <div class="large-1 medium-1 small-6 columns">
            <?php
            if ($index > 0) { //not for first one
                ?>
                <p style="padding-top:8px"><a class="dayForm-remove">DELETE</a></p>
                <?php
                //echo CHtml::link('Remove', array('class' => 'dayForm-remove', 'id' => 'remove_' . $index));
            }
            ?>
        </div>
    </div>
</div>
<script>
    $(function() {

        var days = <?php echo $rule ?>;
        var nowTemp = new Date();
        var startDate = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        startDate.setDate(startDate.getDate() + days);

        window.prettyPrint && prettyPrint();
        $('.toto').fdatepicker({
            format: 'dd/mm/yyyy',
            closeButton: false,
            weekStart: 1,
            onRender: function(date) {
                return date.valueOf() < startDate.valueOf() ? 'disabled' : '';
            }
        });
    });
</script>