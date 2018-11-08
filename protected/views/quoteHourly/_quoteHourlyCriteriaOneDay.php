<?php
$today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
$rule = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
$minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);

$date = $quote->date;

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'hourly-one-quote-form',
    'method' => 'get',
    'enableAjaxValidation' => false,
        ));

echo CHtml::hiddenField('selectedTab', Constants::TAB_HOURLY_ONE);

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
<p class="rc-note">
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
                'name' => '[BookingForClient][date]',
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
            <!--<span id="<?php // echo $spanId    ?>">
            <?php
            // if (isset($date) && $date != '') {
            //    echo Calendar::getDayOfWeekText($date, Calendar::FORMAT_DISPLAYDATE);
            // }
            ?>
            </span>-->
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
            echo CHtml::activeDropDownList($quote, 'startHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'start_hour_one_day'));
            //echo Yii::t('texts', 'HOUR_SEPARATOR');
            echo CHtml::activeDropDownList($quote, 'startMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute_one_day'));
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
            echo CHtml::activeDropDownList($quote, 'endHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'end_hour_one_day'));
            //echo Yii::t('texts', 'HOUR_SEPARATOR');
            echo CHtml::activeDropDownList($quote, 'endMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'end_minute_one_day'));
            ?>
        </td>
        <td class="rc-quote-celltime">
        </td>
    </tr>
</table>
<div class="rc-container-button">
    <span class="buttons">
        <?php
        if ($quote->showResult) {
            //2nd time
            $text = Yii::t('texts', 'BUTTON_UPDATE_QUOTE');
        } else {
            //1st time
            $text = Yii::t('texts', 'BUTTON_GET_A_QUOTE');
        }

        $url = $this->createUrl('clientRegistration/' . ShowPriceAction::NAME);

        echo CHtml::ajaxButton($text, $url, array('type' => 'POST',
            'success' => 'function(html){
                               $("#oneDay").html(html); 
                              }',
            'data' => 'js:$("#hourly-one-quote-form").serialize()',
                ), array('class' => 'rc-button ajaxLoaderButton', 'name' => 'showHourlyQuoteOneDay' . uniqid()));
        ?>
    </span>
    <?php $this->renderPartial('/common/_ajaxLoader', array('html' => true, 'javascript' => true)); ?>
</div>


<?php
$this->endWidget();
?>
<script>
//
//    $(document).ready(function()
//    {
//  
//        $("#HourlyQuoteTabs").tabs({
//            activate: function(event, ui) {
//
//                var activeTab = $("#HourlyQuoteTabs").tabs("option", "active");
//
//                var url = "<?php echo $this->createUrl("clientRegistration/selectTab") ?>";
//
//                $.ajax({
//                    type: "get",
//                    url: url,
//                    data: {selectedTabIndex: activeTab},
//                    //success: function(html) { },
//                    cache: false,
//                    dataType: "html"
//                });
//            }
//        });
//
//    });
</script>