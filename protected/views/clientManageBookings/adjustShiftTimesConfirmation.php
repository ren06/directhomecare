<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_ADJUST_HOURS_CONFIRMATION');
$this->pageSubTitle = Yii::t('texts', 'NOTE_YOU_CAN_ADJUST_TIMES');
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('/common/_backTo', array('scenario' => $scenario)); ?>
        <h4><?php echo Yii::t('texts', 'HEADER_VISIT_REFERENCE') . '&#58;&#160;' . BusinessLogic::getReference($mission); ?></h4>
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
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <table class="rc-quote-control">
            <tr>
                <td class="rc-quote-cellday">
                </td>
                <td class="rc-quote-celltime">
                    <?php echo Yii::t('texts', 'LABEL_PREVIOUS_TIME'); ?>
                </td>
                <td class="rc-quote-celltime">
                    <?php echo Yii::t('texts', 'LABEL_ADJUSTED_TIME') // 'Adjusted Shift times'; ?>
                </td>
            </tr>
            <tr>
                <td class="rc-quote-cellday">
                    <?php echo Yii::t('texts', 'LABEL_DATE'); //$form->labelEx($mission, 'date'); ?>
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
                    <?php echo Yii::t('texts', 'LABEL_START_TIME'); // CHtml::activeLabel($mission, 'StartHour', array('for' => 'StartHourOneDay')); ?>
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
                    echo CHtml::activeDropDownList($mission, 'StartHour', Calendar::getDropDownHours(Constants::HOURLY), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'start_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($mission, 'StartMinute', Calendar::getDropDownMinutes(), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'start_minute_one_day'));
                    ?>
                </td>
            </tr>
            <tr>
                <td class="rc-quote-cellday">
                    <?php echo Yii::t('texts', 'LABEL_END_TIME'); // $form->labelEx($mission, 'EndHour'); ?>
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
                    echo CHtml::activeDropDownList($mission, 'EndHour', Calendar::getDropDownHours(Constants::HOURLY), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'end_hour_one_day'));
                    //echo Yii::t('texts', 'HOUR_SEPARATOR');
                    echo CHtml::activeDropDownList($mission, 'EndMinute', Calendar::getDropDownMinutes(), array('disabled' => 'disabled', 'class' => 'rc-drop', 'id' => 'end_minute_one_day'));
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>