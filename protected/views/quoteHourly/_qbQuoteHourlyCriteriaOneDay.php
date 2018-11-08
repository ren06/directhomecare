<?php
$today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
$rule = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
$minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);

$date = $quote->date;

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'qb-one-day-form',
    'enableAjaxValidation' => false,
        ));

$err = $quote->errors;
?>
<?php if ($quote->hasErrors()) { ?>
    <div class="flash-error">
        <?php
        echo $form->error($quote, 'date');
        echo $form->error($quote, 'endHour');
        ?>
    </div>
<?php } ?>
<p class="note">
    <?php echo Yii::t('texts', 'NOTE_FOR_ONE_OFF_VISITS'); ?>
</p>
<table class="rc-quote-control">
    <tr>
        <td class="rc-quote-cellbutton">
        </td>
        <td class="rc-quote-cellday">
            <?php echo $form->labelEx($quote, 'date'); ?>
        </td>
        <td class="rc-quote-celltime">
            <?php
            $dateClass = $quote->hasErrors('date') ? CHtml::$errorCss : '';
            $spanId = 'dateDay';
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'date',
                'value' => $date,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1', //Monday
                    'minDate' => $minDate,
                    'maxDate' => '+730',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { handleDateSelect(dateText, '$spanId'); }"
                )
                ,
                'htmlOptions' => array('class' => 'rc-field-medium ' . $dateClass),));
            ?>
        </td>
        <td class="rc-quote-celltime">
            <span id="<?php echo $spanId ?>">
                <?php
                if (isset($date) && $date != '') {
                    echo Calendar::getDayOfWeekText($date, Calendar::FORMAT_DISPLAYDATE, false);
                }
                ?>
            </span>
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
        </td>
        <td class="rc-quote-cellday">
            <?php echo CHtml::activeLabel($quote, 'startHour', array('for' => 'StartHourOneDay')); ?>
            <span class="required greyableHourly">*</span>
        </td>
        <td class="rc-quote-celltime">
            <?php
            echo CHtml::activeDropDownList($quote, 'startHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop qb-change-hours', 'id' => 'start_hour_one_day'));
            echo CHtml::activeDropDownList($quote, 'startMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop qb-change-hours', 'id' => 'start_minute_one_day'));
            ?>
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
        </td>
        <td class="rc-quote-cellday">
            <?php echo $form->labelEx($quote, 'endHour'); ?>
        </td>
        <td class="rc-quote-celltime">
            <?php
            echo CHtml::activeDropDownList($quote, 'endHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop qb-change-hours', 'id' => 'end_hour_one_day'));
            echo CHtml::activeDropDownList($quote, 'endMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop qb-change-hours', 'id' => 'end_minute_one_day'));
            ?>
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
        </td>
        <td class="rc-quote-cellday">
            <?php echo Chtml::label('PRICE', 'price') ?>
        </td>
        <td class="rc-quote-celltime">
            <?php
            
            ?>
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
</table>

<?php
$this->endWidget();
?>