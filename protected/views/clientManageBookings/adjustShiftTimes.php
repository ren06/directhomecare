<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_ADJUST_HOURS');
$this->pageSubTitle = Yii::t('texts', 'NOTE_YOU_CAN_ADJUST_TIMES');
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('/common/_backTo', array('scenario' => $scenario)); ?>
        <h4><?php echo Yii::t('texts', 'HEADER_VISIT_REFERENCE') . '&#58;&#160;' . BusinessLogic::getReference($mission); ?></h4>
    </div>
</div>
<div class="row">
    <div class="large-6 medium-8 small-12 columns">
        <?php
        $minDate = '';
        $date = Calendar::convert_DBDateTime_DisplayDate($mission->start_date);
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'correct-times-form',
            'method' => 'post',
            'enableAjaxValidation' => false,
        ));

        CHtml::hiddenField('id', $mission->id);
        CHtml::hiddenField('scenario', $scenario);

        $err = $mission->errors;
        ?>
        <?php if (Yii::app()->user->hasFlash('error_times')): ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('error_times') ?>
            </div>
        <?php endif ?>

        <table class="rc-quote-control">
            <tr>
                <td class="rc-quote-cellday">
                </td>
                <td class="rc-quote-celltime">
                    <?php echo Yii::t('texts', 'LABEL_ORGINAL_TIME'); ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php echo Yii::t('texts', 'LABEL_ACTUAL_TIME'); ?>
                </td>
            </tr>
            <tr>
                <td class="rc-quote-cellday">
                    <?php echo Yii::t('texts', 'LABEL_DATE'); // $form->labelEx($mission, 'date'); ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    $dateClass = $mission->hasErrors('date') ? CHtml::$errorCss : '';
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
                        'htmlOptions' => array('disabled' => 'disabled', 'class' => 'rc-field-medium ' . $dateClass),));
                    ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    $dateClass = $mission->hasErrors('date') ? CHtml::$errorCss : '';
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
                        'htmlOptions' => array('disabled' => 'disabled', 'class' => 'rc-field-medium ' . $dateClass),));
                    ?>
                </td>
            </tr>
            <tr>
                <td class="rc-quote-cellday">
                    <?php echo Yii::t('texts', 'LABEL_START_TIME'); // echo CHtml::activeLabel($mission, Yii::t('texts', 'LABEL_START_TIME'), array('for' => 'StartHourOneDay')); ?>
                    <span class="required greyableHourly">*</span>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    echo CHtml::activeDropDownList($originalMission, 'StartHour', Calendar::getDropDownHours(Constants::HOURLY), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'start_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($originalMission, 'StartMinute', Calendar::getDropDownMinutes(), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'start_minute_one_day'));
                    ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    echo CHtml::activeDropDownList($mission, 'StartHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'start_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($mission, 'StartMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute_one_day'));
                    ?>
                </td>
            </tr>
            <tr>
                <td class="rc-quote-cellday">
                    <?php echo Yii::t('texts', 'LABEL_END_TIME'); //$form->labelEx($mission, Yii::t('texts', 'LABEL_END_TIME')); ?>
                    <span class="required greyableHourly">*</span>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    echo CHtml::activeDropDownList($originalMission, 'EndHour', Calendar::getDropDownHours(Constants::HOURLY), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'end_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($originalMission, 'EndMinute', Calendar::getDropDownMinutes(), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'end_minute_one_day'));
                    ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php
                    echo CHtml::activeDropDownList($mission, 'EndHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'end_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($mission, 'EndMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'end_minute_one_day'));
                    ?>
                </td>
            </tr>
        </table>

        <?php
        echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CALCULATE_DIFFERENCE'), array('class' => 'button expand', 'name' => 'calculate_button'));
        ?>
        <?php
        if (isset($result)) {
            ?>
            <div class="rc-module-inside" id="credit_card" style="display:visible">
                <?php if (Yii::app()->user->hasFlash('error_card')): ?>
                    <div class="flash-error">
                        <?php echo Yii::app()->user->getFlash('error_card') ?>
                    </div>
                <?php endif ?>
                <div>
                    <?php
                    $toPay = $result['toPay'];
                    $toPayCard = $result['toPayCard'];
                    $toPayVoucher = $result['toPayVoucher'];
                    $extraTime = $result['extraTime'];
                    echo Yii::t('texts', 'NOTE_AMOUNT_TO_PAY_TO_COVER') . ' <b>' . $extraTime . ': ' . $toPay->text . '</b>';
                    if ($toPayVoucher->amount > 0) {

                        echo '<br /><br /> To pay with voucher: ' . $toPayVoucher->text;

                        if ($toPayCard->amount > 0) {
                            echo '<br /><br /> To pay by card: ' . $toPayVoucher->text;
                        }
                    }
                    ?>
                </div>
                <br />
                <?php
                if ($toPayCard->amount > 0) {

                    $this->renderPartial('/creditCard/_selectOrNewCreditCard', array('form' => $form,
                        'client' => $client,
                        'creditCard' => $creditCard,
                        'creditCards' => $creditCards,
                        'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton,
                        'selectedBillingAddressRadioButton' => '',
                        'billingAddress' => ''));

                    //include Yii::app()->basePath . '/views/creditCard/_cardLogo.php';
                }

                echo CHtml::hiddenField('missionId', $mission->id);
                echo CHtml::hiddenField('amount', $toPay->amount);
                echo CHtml::hiddenField('currency', $toPay->currency);
                ?>
                <div class="rc-container-button">
                    <div class="buttons">
                        <?php
                        if (Yii::app()->params['test']['allowClientPayment'] == true) {
                            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_PAY_ADJUST_TIME'), array('class' => 'button expand', 'name' => 'pay_button'));
                        } else {
                            echo Html::disabledButton(Yii::t('texts', 'BUTTON_PAY_ADJUST_TIME'));
                        }
                        ?>
                    </div>  
                    <?php $this->renderPartial('/common/_ajaxLoader', array('text' => '<b>' . Yii::t('texts', 'NOTE_SECURELY_PROCESSING') . '</b><br /><br />', 'buttonId')); ?> 
                </div>
            </div>
            <?php
        }
        $this->endWidget();
        ?>
    </div>
</div>