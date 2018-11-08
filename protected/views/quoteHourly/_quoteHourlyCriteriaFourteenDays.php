<?php
//buisness logic, should be moved somewhere els
//$startTime = $quote->start_time;
//$endTime = $quote->end_time;

$today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
$rule = BusinessRules::getNewBookingDelay_Hourly_Fourteen_InDays();
$minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);
$startDate = $quote->getStartDate(false);
$endDate = $quote->getEndDate(false);

//end buisness logic
$formId = 'hourly-fourteen-quote-form';
$form = $this->beginWidget('CActiveForm', array(
    'id' => $formId,
    'method' => 'get',
    'enableAjaxValidation' => false,
        ));

echo CHtml::hiddenField('selectedTab', Constants::TAB_HOURLY_FOURTEEN);

$err = $quote->errors;
?>
<?php if ($quote->hasErrors() || $quote->dayFormsHaveErrors()) { ?>
    <div class="flash-error">
        <?php
        if ($quote->hasErrors()) {
            echo $form->error($quote, 'start_date_time');
            echo $form->error($quote, 'end_date_time');
        } else {
            $errorsArray = $quote->getDayFormsErrorMessages();

            echo $errorsArray[0]['startHour'][0] . '<br />';
        }
        ?>
    </div>
<?php } ?>
<p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_FOR_UP_TO_14'); ?>
</p>
<table class="rc-quote-control">
    <tr>
        <td class="rc-quote-cellbutton"></td>
        <td class="rc-quote-cellday">
            <?php echo $form->labelEx($quote, 'start_date', array('for' => 'hourlyStartDate')); ?>
        </td>
        <td class="rc-quote-celltime">
            <?php
            $spanId = 'startDateDayFourteen';
            $urlStartDate = $this->createUrl("site/startDateSelect");

            $startDateClass = $quote->hasErrors('start_date') ? CHtml::$errorCss : '';
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'start_date',
                'value' => $startDate,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1', //Monday
                    'minDate' => $minDate,
                    'maxDate' => '+730',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { 
                        handleAJAX(dateText,'$urlStartDate', 'startDate'); 
                        handleDateSelect(dateText, '$spanId');
                      }"
                )
                ,
                'htmlOptions' => array('id' => 'hourlyStartDate', 'class' => 'rc-field-medium ' . $startDateClass),));
            ?>

<!--            <span id="<?php // echo $spanId ?>">
                <?php
                // if (isset($startDate) && $startDate != '') {
                //    echo Calendar::getDayOfWeekText($startDate, Calendar::FORMAT_DISPLAYDATE);
                // }
                ?>
            </span>-->
        </td>
        <td class="rc-quote-celltime"></td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
            <?php
            // echo CHtml::radioButton("Recurring", ($quote->recurring ? false : true), array('class' => 'toggleRadioButton', 'name' => 'radioNotRecurringHourly', 'id' => 'radioNotRecurringHourly', 'value' => false, 'uncheckValue' => null)) . '&#160;'; 
            ?>
        </td>
        <td class="rc-quote-cellday">
            <?php echo $form->labelEx($quote, 'end_date', array('for' => 'hourlyEndDate')); ?>
        </td>
        <td class="rc-quote-celltime greyableDropHourly greyableField">
            <?php
            $urlEndDate = $this->createUrl("site/endDateSelect");
            $spanId = 'endDateDayFourteen';
            $endDateClass = $quote->hasErrors('end_date') ? CHtml::$errorCss : '';
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'end_date',
                'value' => $endDate,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1',
                    'minDate' => $minDate,
                    'maxDate' => '+731',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { 
                        handleAJAX(dateText,'$urlEndDate', 'endDate'); 
                        handleDateSelect(dateText, '$spanId');
                       }"
                ),
                'htmlOptions' => array('id' => 'hourlyEndDate', 'class' => 'rc-field-medium ' . $endDateClass),));
            ?>
<!--            <span id="<?php // echo $spanId ?>">
                <?php
                // if (isset($endDate) && $endDate != '') {
                //    echo Calendar::getDayOfWeekText($endDate, Calendar::FORMAT_DISPLAYDATE);
                // }
                ?>
            </span>-->
        </td>
        <td class="rc-quote-celltime"></td>
    </tr>
<!--<tr>
        <td class="rc-quote-cellbutton">
    <?php
    //echo CHtml::radioButton("Recurring", $quote->recurring, array('class' => 'toggleRadioButton', 'name' => 'radioRecurringHourly', 'id' => 'radioRecurringHourly', 'value' => true, 'uncheckValue' => null));
    ?>
        </td>
        <td class="rc-quote-cellday" colspan="3">
            <span><?php echo CHtml::label(Yii::t('texts', 'LABEL_UNTIL_NOTICE'), 'radioRecurringHourly'); ?></span>
        </td>
    </tr>-->
</table>
<div id="weekDays">
    <?php
    $output = $this->renderPartial('/quoteHourly/_weekDays', array('quote' => $quote), true);
    echo $output;
    ?>
</div>
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
                               $("#fourteenDays").html(html);
                              }',
            'data' => "js:$('#$formId').serialize()",
                ), array('class' => 'rc-button ajaxLoaderButton', 'name' => 'showHourlyQuoteFourteen' . uniqid()));
        ?>
    </span>        
    <?php $this->renderPartial('/common/_ajaxLoader', array('html' => true, 'javascript' => true)); ?>
</div>
<?php
$this->endWidget();
?>
<script type="text/javascript">

    function convertDate(date) {// 31/12/2014

        var year = date.substr(6, 4);
        var month = date.substr(3, 2);
        var day = date.substr(0, 2);

        return new Date(year, month - 1, day);
    }

    function handleAJAX(dateText, url, date) { //startDate or endDate

        if (date === 'startDate') {
            //see _criteria
            handleEndDateDefaultLogic('hourly', dateText);
        }

        $.ajax({
            type: "post",
            url: url,
            data: {selDate: dateText, formDays: decodeURIComponent($("#<?php echo $formId ?>").serialize())},
            success: function(html) {

                var content = html;
                $("#weekDays").html(content);
            },
            cache: false,
            dataType: "html"
        });

    }

    function handleNotRecurringHourly() {
        $(".greyableHourly.required").css("visibility", "visible");
    }

    function handleRecurringHourly() {

        $(".greyableHourly.required").css("visibility", "hidden")
        $(".greyableHourly.rc-error").hide();
    }

    $(document).ready(function()
    {
        //if ($("#radioNotRecurringHourly").is(":checked")) {
        handleNotRecurringHourly();
        //        }
        //        else {
        //            handleRecurringHourly();
        //        }

        $("#radioNotRecurringHourly").click(function() { //end date            
            if ($(this).is(":checked")) {

                handleNotRecurringHourly();

                var dateText = $("#hourlyEndDate").val();
                var url = "<?php echo $this->createUrl("site/endDateSelect") ?>";

                handleAJAX(dateText, url);

            }
        });

        $("#radioRecurringHourly").click(function() {  //until further notice           
            if ($(this).is(":checked")) {
                handleRecurringHourly();

                var dateText = "";
                var url = "<?php echo $this->createUrl("site/untilFurtherNoticeSelect") ?>";

                handleAJAX(dateText, url);

            }
        });

        $("#end_dateHourly").click(function() { //end date            

            $("#radioNotRecurringHourly").attr('checked', true);
            handleNotRecurringHourly();

        });

        $(".greyableDropHourly :input").click(function() { //end date            

            $("#radioNotRecurringHourly").attr('checked', true);
            handleNotRecurringHourly();

        });
    }
    );

</script>