<?php
$physical_conditions_errors = 'physical_conditions_errors';
$mental_conditions_errors = 'mental_conditions_errors';

if ($index != -1) {

    $physical_conditions_errors = $physical_conditions_errors . '_' . $index;
    $mental_conditions_errors = $mental_conditions_errors . '_' . $index;
}


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
// Physical Abilities
$physicalConditions = Condition::getConditions(Condition::TYPE_PHYSICAL);
?>

<div class="rc-container-conditions">
    <h3><?php echo $conditionTypes[Condition::TYPE_PHYSICAL]; ?></h3>
    <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span></p>
    <div id="<?php echo $physical_conditions_errors ?>" class="rc-error">
        <?php
        if (isset($errors[$physical_conditions_errors])) {
            echo $errors[$physical_conditions_errors];
        }
        ?>
    </div>
    <?php
    $i = 0;
    $lastEntry = count($physicalConditions);
    foreach ($physicalConditions as $physicalCondition) {

        $i++
        ?>
        <div class="rc-container-conditions-tickbox">
            <?php
            $id = $physicalCondition->id;

            $value = isset($carerConditionsKeyed[$physicalCondition->id]);

            if ($index >= 0) {
                $attributeName = 'condition_' . $index . '_';
            } else {
                $attributeName = 'condition_';
            }

            $attributeName = $attributeName . $physicalCondition->id;
            echo CHtml::CheckBox($attributeName, $value, array('class' => 'physical_checkboxes', 'id' => $attributeName)) . "\n"; // RC the n is to display checkox and label on 2 different lines to correct the bug where input is not closed
            echo CHtml::label(Condition::getText($physicalCondition->name), $attributeName);
            ?>
        </div>
        <?php
    }
    ?>
</div> 
<?php
// Mental Abilities
$mentalConditions = Condition::getConditions(Condition::TYPE_MENTAL);
?>
<div class="rc-container-conditions">
    <h3><?php echo $conditionTypes[Condition::TYPE_MENTAL]; ?></h3>
    <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span></p>
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
            $value = isset($carerConditionsKeyed[$mentalCondition->id]);

            if ($index >= 0) {
                $attributeName = 'condition_' . $index . '_';
            } else {
                $attributeName = 'condition_';
            }

            $attributeName = $attributeName . $mentalCondition->id;

            echo CHtml::CheckBox($attributeName, $value, array('class' => '', 'id' => $attributeName)) . "\n";
            echo CHtml::label(Condition::getText($mentalCondition->name), $attributeName);
            ?>
        </div>
        <?php
    }
    ?>
</div>
<?php if ($model instanceof ServiceUser) { ?>
    <div class="rc-container-conditions">
        <h3><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_USER'); //byRC  ?><?php //echo CHtml::activeLabelEx($model, "[$index]note");  ?></h3>
        <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_500'); ?></p>
        <div id="<?php //byRC echo $note_errors  ?>" class="rc-error">
            <?php echo CHtml::error($model, "[$index]note"); ?>
        </div>
        <?php
        echo CHtml::activeTextArea($model, "[$index]note", array('class' => 'rc-textarea-notes'));
        ?>
    </div>
    <?php
}
?>