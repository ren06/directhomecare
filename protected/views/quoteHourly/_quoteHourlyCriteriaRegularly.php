<?php
$formId = 'hourly-regularly-quote-form';
$form = $this->beginWidget('CActiveForm', array(
    'id' => $formId,
    'method' => 'get',
    'enableAjaxValidation' => false,
        ));

echo CHtml::hiddenField('selectedTab', Constants::TAB_HOURLY_REGULARLY);

$err = $quote->errors;
?>
<?php if ($quote->hasErrors() || $quote->dayFormsHaveErrors()) { ?>
    <div class="flash-error">
        <?php
        if ($quote->hasErrors()) {
            echo $form->error($quote, 'startDate');
            echo $form->error($quote, 'endDate');
        }
        if ($quote->dayFormsHaveErrors()) {

            $errorsArray = $quote->getDayFormsErrorMessages();

            echo $errorsArray[0]['startHour'][0] . '<br />';
        }
        ?>
    </div>
<?php } ?>
<p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_FOR_REGULARLY_SCHEDULED'); ?>
</p>
<div id="weekDays">
    <?php
    $output = $this->renderPartial('/quoteHourly/_weekDays', array('quote' => $quote), true);
    echo $output;
    ?>
</div>
<br />
<br />
<br />
<table class="rc-quote-control">
    <tr>
        <td class="rc-quote-cellbutton"></td>
        <td class="rc-quote-cellday">
            <?php echo $form->labelEx($quote, 'startDate', array('for' => 'regularlyStartDate')); ?>
        </td>
        <td class="rc-quote-celltime">
            <?php
            $today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
            $rule = BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays();
            $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);
            $spanId = 'startDateDayRegularly';
            $startDateClass = $quote->hasErrors('startDate') ? CHtml::$errorCss : '';
            $startDate = $quote->getStartDate();

            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'startDate',
                'value' => $startDate,
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
            <!--<span id="<?php // echo $spanId  ?>">
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
            echo CHtml::radioButton("Recurring", ($quote->recurring ? false : true), array('class' => 'toggleRadioButton',
                'name' => 'radioNotRecurringRegularly',
                'id' => 'radioNotRecurringRegularly',
                'value' => false, 'uncheckValue' => null));
            ?>
        </td>
        <td class="rc-quote-cellday">
            <?php
            $endDateClass = $quote->hasErrors('endDate') ? CHtml::$errorCss : '';
            echo CHtml::label(Yii::t('texts', 'LABEL_END_DATE'), 'radioNotRecurringRegularly'
                    , array('class' => $endDateClass));
            ?>
            <span class="required greyableNotRecurringRegularly">*</span>
        </td>
        <td class="rc-quote-celltime">
            <?php
            $spanId = 'endDateDayRegularly';
            $endDate = $quote->getEndDate();
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'endDate',
                'value' => $endDate,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1',
                    'minDate' => $minDate,
                    'maxDate' => '+731',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                    'onSelect' => "js: function(dateText, inst) { handleDateSelect(dateText, '$spanId');}"
                ),
                'htmlOptions' => array('id' => 'regularlyEndDate', 'class' => 'rc-field-medium greyableNotRecurringRegularly ' . $endDateClass),));
            ?>
            <!--<span id="<?php // echo $spanId  ?>">
            <?php
            // if (isset($endDate) && $endDate != '') {
            //    echo Calendar::getDayOfWeekText($endDate, Calendar::FORMAT_DISPLAYDATE);
            // }
            ?>
            </span>-->
        </td>
    </tr>
    <tr>
        <td class="rc-quote-cellbutton">
            <?php
            echo CHtml::radioButton("Recurring", $quote->recurring, array('class' => 'toggleRadioButton', 'name' => 'radioRecurringHourly', 'id' => 'radioRecurringHourly', 'value' => true, 'uncheckValue' => null));
            ?>
        </td>
        <td class="rc-quote-cellday" colspan="3">
            <span><?php echo CHtml::label(Yii::t('texts', 'LABEL_UNTIL_NOTICE'), 'radioRecurringRegularly', array('class' => 'greyableRecurringRegularly'));
            ?></span>
            <span class="required greyableRecurringRegularly">*</span>
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
                               $("#regularly").html(html);
                              }',
            'data' => "js:$('#$formId').serialize()",
                ), array('class' => 'rc-button ajaxLoaderButton', 'name' => 'showHourlyQuoteRegularly' . uniqid()));
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

    function handleNotRecurringRegularly() {
        $(".greyableNotRecurringRegularly.required").css("visibility", "visible");
        $(".greyableRecurringRegularly.required").css("visibility", "hidden");
    }

    function handleRecurringHourlyRegularly() {

        $(".greyableRecurringRegularly.required").css("visibility", "visible");
        $(".greyableNotRecurringRegularly.required").css("visibility", "hidden");
    }

    $(document).ready(function()
    {

        if ($("#radioNotRecurringRegularly").is(":checked")) {


            handleNotRecurringRegularly();
        }
        else {

            handleRecurringHourlyRegularly();
        }

        $("#radioNotRecurringRegularly").click(function() { //end date            
            if ($(this).is(":checked")) {

                handleNotRecurringRegularly();
            }
        });

        $("#radioRecurringRegularly").click(function() {  //until further notice           
            if ($(this).is(":checked")) {
                handleRecurringHourlyRegularly();
            }
        });

        $("#regularlyEndDate").click(function() { //end date            

            $("#radioNotRecurringRegularly").attr('checked', true);
            handleNotRecurringRegularly();

        });

        $(".greyableNotRecurringRegularly :input").click(function() { //end date            

            $("#radioNotRecurringRegularly").attr('checked', true);
            handleNotRecurringRegularly();

        });
    }
    );

</script>
