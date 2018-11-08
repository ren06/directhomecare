<?php
$physical_conditions_errors = 'physical_conditions_errors';
$mental_conditions_errors = 'mental_conditions_errors';
$activities_errors = 'activities_errors';

if ($index == -1) {

    $suffix = '';
    $suffix2 = '';
} else {
    $suffix = $index . '_';
    $suffix2 = '_' . $index;
}

$physical_conditions_errors = $physical_conditions_errors . $suffix2;
$mental_conditions_errors = $mental_conditions_errors . $suffix2;
$activities_errors = $activities_errors . $suffix2;

$conditionTypes = Condition::getTypes();

if ($model instanceof ServiceUser) {
    $carerConditions = $model->serviceUserConditions;
} else {
    $carerConditions = $model->carerConditions;
}
$carerConditionsKeyed = array();

foreach ($carerConditions as $carerCondition) {

    $carerConditionsKeyed[$carerCondition->id_condition] = true;
}
?>

<?php
// Activities
$activities = Condition::getConditions(Condition::TYPE_ACTIVITY);
?>
<h3><?php echo $conditionTypes[Condition::TYPE_ACTIVITY]; ?></h3>
<p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span></p>
<div id="<?php echo $activities_errors ?>" class="rc-error">
    <?php
    if (isset($errors[$activities_errors])) {
        echo $errors[$activities_errors];
    }
    ?>
</div>
<?php
foreach ($activities as $activity) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $id = $activity->id;

        $value = isset($carerConditionsKeyed[$activity->id]);

        $attributeName = 'condition_' . $suffix;

        $attributeName = $attributeName . $activity->id;
        echo CHtml::CheckBox($attributeName, $value, array('class' => 'activity_checkboxes', 'id' => $attributeName)) . "\n"; // RC the n is to display checkox and label on 2 different lines to correct the bug where input is not closed           
        echo CHtml::label(Condition::getTextCarer($activity->name), $attributeName);
        ?>
    </div>
    <?php
}
// Physical Abilities
$physicalConditions = Condition::getConditions(Condition::TYPE_PHYSICAL);
?>
<h3><?php echo $conditionTypes[Condition::TYPE_PHYSICAL]; ?></h3>
<p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_THE_CONDITION'); ?><span class="required">*</span></p>
<div id="<?php echo $physical_conditions_errors ?>" class="rc-error">
    <?php
    if (isset($errors[$physical_conditions_errors])) {
        echo $errors[$physical_conditions_errors];
    }
    ?>
</div>
<?php
foreach ($physicalConditions as $physicalCondition) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $id = $physicalCondition->id;

        $checked = isset($carerConditionsKeyed[$id]);

        $attributeName = 'condition_' . $suffix;

        $attributeName = $attributeName . $physicalCondition->id;
        //echo CHtml::CheckBox($attributeName, $value, array('class' => 'physical_checkboxes', 'id' => $attributeName)) . "\n"; // RC the n is to display checkox and label on 2 different lines to correct the bug where input is not closed

        echo CHtml::radioButton('physical_condition' . $suffix2, $checked, array('value' => $attributeName, 'class' => 'toggleRadioButton', 'id' => $attributeName, 'uncheckValue' => null));

        echo CHtml::label(Condition::getTextCarer($physicalCondition->name), $attributeName);
        ?>
    </div>
    <?php
}
// Mental Abilities
$mentalConditions = Condition::getConditions(Condition::TYPE_MENTAL);
?>
<h3><?php echo $conditionTypes[Condition::TYPE_MENTAL]; ?></h3>
<p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_THE_CONDITION'); ?><span class="required">*</span></p>
<div id="<?php echo $mental_conditions_errors ?>" class="rc-error">
    <?php
    if (isset($errors[$mental_conditions_errors])) {
        echo $errors[$mental_conditions_errors];
    }
    ?>
</div>
<?php
foreach ($mentalConditions as &$mentalCondition) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $checked = isset($carerConditionsKeyed[$mentalCondition->id]);

        $attributeName = 'condition_' . $suffix;

        $attributeName = $attributeName . $mentalCondition->id;

        //echo CHtml::CheckBox($attributeName, $value, array('class' => '', 'id' => $attributeName)) . "\n";
        echo CHtml::radioButton('mental_condition' . $suffix2, $checked, array('value' => $attributeName, 'class' => 'toggleRadioButton', 'id' => $attributeName, 'uncheckValue' => null));

        echo CHtml::label(Condition::getTextCarer($mentalCondition->name), $attributeName);
        ?>
    </div>
    <?php
}
if ($model instanceof ServiceUser) {
    ?>
    <h3><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_USER'); //byRC     ?><?php //echo CHtml::activeLabelEx($model, "[$index]note");            ?></h3>
    <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_500'); ?></p>
    <div id="<?php //byRC echo $note_errors    ?>" class="rc-error">
        <?php echo CHtml::error($model, "[$index]note"); ?>
    </div>
    <?php
    echo CHtml::activeTextArea($model, "[$index]note", array('class' => 'rc-textarea-notes'));
}
?>