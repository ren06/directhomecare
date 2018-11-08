<?php


$formId = 'clientFilters';
echo CHtml::beginForm('', 'post', array('id' => $formId));

$attributeName = 'clientId';
echo CHtml::dropDownList($attributeName, $clientId, $clients);

echo '<br>';
$today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
$rule = BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays();
$minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);
echo 'Start Date';
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
     
    )
    ,
    'htmlOptions' => array('id' => 'hourlyStartDate', 'class' => 'rc-field-medium'),));

echo '<br>';
echo 'End Date';
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
    ),
    'htmlOptions' => array('id' => 'hourlyEndDate', 'class' => 'rc-field-medium'),));
?>
<br>
<?php

echo CHtml::submitButton();
echo CHtml::endForm();


if($result != ''){
    
    echo 'Result: <br>' . $result;
}
?>