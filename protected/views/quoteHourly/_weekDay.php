<?php

//$checkBoxId = 'check_box_' . $index;
$date = $model->date;
$st = $model->startHour;
$en = $model->endHour;
$checkBoxId = "DayForm_" . $model->scenario . '_' . $index . "_selected";

echo '<td class="rc-quote-cellbutton">' . CHtml::activeCheckBox($model, "[$index]selected", array('id' => $checkBoxId)) . '</td>';

if ($model->scenario == Constants::TAB_HOURLY_FOURTEEN) {
    $text = $model->getDayOfWeekText(false) . ' ' . Calendar::convert_DBDateTime_DisplayDateText($model->date);
}
else{
    $text = $model->getDayOfWeekText(false);
}

if($model->hasErrors()){
    $css = CHtml::$errorCss;
}
else{
    $css = '';
}

echo '<td class="rc-quote-cellday">' . CHtml::label($text, $checkBoxId, array('class' => $css));

if ($model->scenario == Constants::TAB_HOURLY_FOURTEEN) {
    echo CHtml::activeHiddenField($model, "[$index]date") . '</td>';
} else {
    echo '</td>';
}


echo '<td class="rc-quote-celltime">' . CHtml::activeDropDownList($model, "[$index]startHour", Calendar::getDropDownHours(Calendar::DROPDOWNHOURS_HOURLY), array('class' => 'rc-drop'));

echo CHtml::activeDropDownList($model, "[$index]startMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop')) . '</td>';

echo '<td class="rc-quote-celltime">' . CHtml::activeDropDownList($model, "[$index]endHour", Calendar::getDropDownHours(Calendar::DROPDOWNHOURS_HOURLY), array('class' => 'rc-drop'));

echo CHtml::activeDropDownList($model, "[$index]endMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop'));

echo '</td>';
?>