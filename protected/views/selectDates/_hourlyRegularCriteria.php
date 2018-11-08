<div class="rc-module-bar">
    <div class="rc-module-name">
        <?php // echo Yii::t('texts', 'NOTE_FOR_ONE_OFF_VISITS'); ?>
        Regularly scheduled
    </div>
</div>
<table class="rc-calendar">
    <tr>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_MONDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_TUESDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_WEDNESDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_THURSDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_FRIDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_SATURDAY'); ?></td>
        <td class="rc-calendar-week"><?php echo Yii::t('texts', 'LABEL_SUNDAY'); ?></td>
    </tr>
    <tr>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
        <td class="rc-calendar-day">
            <span class = "day_content">
                <?php echo CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
            </span>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class="rc-quote-cellbutton">
        </td>
        <td class="rc-quote-cellday">
        </td>
        <td class="rc-quote-celltime">
            <?php
            $today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
            $rule = BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays();
            $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);
            $spanId = 'startDateDayRegularly';
            $startDateClass = $quote->hasErrors('startDate') ? CHtml::$errorCss : '';
            // $startDate = $quote->getStartDate();

            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'startDate',
                // 'value' => $startDate,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1', //Monday
                    'minDate' => $minDate,
                    'maxDate' => '+730',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { handleDateSelect(dateText, '$spanId');}"
                )
                ,
                'htmlOptions' => array('id' => 'regularlyStartDate', 'class' => 'rc-field-medium ' . $startDateClass),));
            ?>
                    <!--<span id="<?php // echo $spanId    ?>">
            <?php
            // if (isset($startDate) && $startDate != '') {
            //    echo Calendar::getDayOfWeekText($startDate, Calendar::FORMAT_DISPLAYDATE);
            // }
            ?>
                    </span>-->
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
            <?php
            //echo CHtml::radioButton("Recurring", ($quote->recurring ? false : true), array('class' => 'toggleRadioButton',
            //    'name' => 'radioNotRecurringRegularly',
            //    'id' => 'radioNotRecurringRegularly',
            //    'value' => false, 'uncheckValue' => null));
            ?>
        </td>
        <td class="rc-quote-cellday">
            <?php
            // $endDateClass = $quote->hasErrors('endDate') ? CHtml::$errorCss : '';
            // echo CHtml::label(Yii::t('texts', 'LABEL_END_DATE'), 'radioNotRecurringRegularly'
            //        , array('class' => $endDateClass));
            ?>
            <span class="required greyableNotRecurringRegularly">*</span>
        </td>
        <td class="rc-quote-celltime">
            <?php
            $spanId = 'endDateDayRegularly';
            // $endDate = $quote->getEndDate();
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'endDate',
                // 'value' => $endDate,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1',
                    'minDate' => $minDate,
                    'maxDate' => '+731',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { handleDateSelect(dateText, '$spanId');}"
                ),
                    // 'htmlOptions' => array('id' => 'regularlyEndDate', 'class' => 'rc-field-medium greyableNotRecurringRegularly ' . $endDateClass),
            ));
            ?>
            <!--<span id="<?php // echo $spanId    ?>">
            <?php
            // if (isset($endDate) && $endDate != '') {
            //    echo Calendar::getDayOfWeekText($endDate, Calendar::FORMAT_DISPLAYDATE);
            // }
            ?>
            </span>-->
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
            <?php
            // echo CHtml::radioButton("Recurring", $quote->recurring, array('class' => 'toggleRadioButton', 'name' => 'radioRecurringHourly', 'id' => 'radioRecurringHourly', 'value' => true, 'uncheckValue' => null));
            ?>
        </td>
        <td class="rc-quote-cellday" colspan="3">
            <span><?php echo CHtml::label(Yii::t('texts', 'LABEL_UNTIL_NOTICE'), 'radioRecurringRegularly', array('class' => 'greyableRecurringRegularly'));
            ?></span>
            <span class="required greyableRecurringRegularly">*</span>
        </td>
</table>


<?php
$url = $this->createUrl('site/changeDateTime');
$buttonHtml = CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select'));
?>

<script type="text/javascript">

    $(document).on("click", ".button-select", function() {

        var button = $(this);

        $.ajax({
            success: function(html) {

                button.closest('span').html(html);

            },
            type: 'post',
            url: '<?php echo $this->createUrl('site/calendarSelect') ?>',
            cache: false,
            dataType: 'html'
        });
    });

    $(document).on("click", ".button-delete", function() {

        var html = '<?php echo $buttonHtml ?>';

        var button = $(this);
        button.closest('span').html(html);
    });
</script>