<script type="text/javascript">
    $(document).ready(function()
    {
        $("#Carer_live_in_0").click(function() {

            if ($(this).is(":checked")) {
                $("#live_in_work_radius").show("slow");
            }
        });

        $("#Carer_live_in_1").click(function() {

            if ($(this).is(":checked")) {
                $("#live_in_work_radius").hide("slow");
            }
        });

        $("#Carer_hourly_work_0").click(function() {

            if ($(this).is(":checked")) {
                $("#hourly_work_schedule").show("slow");
                $("#hourly_work_radius").show("slow");
            }
        });

        $("#Carer_hourly_work_1").click(function() {

            if ($(this).is(":checked")) {
                $("#hourly_work_schedule").hide("slow");
                $("#hourly_work_radius").hide("slow");
            }
        });
    });
</script>
<style type="text/css">
    #myinformation {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>
<?php
$this->pageTitle = Yii::t('texts', 'HEADER_TYPE_OF_WORK');
$this->pageSubTitle = 'How can you help ?';
?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'carer_type_work-form', 'enableAjaxValidation' => false,
    'stateful' => true,
        ));
?>

<div class="row">
    <div class="columns large-12 medium-12 small-12">
        <?php
        if ($maintain == true) {
            $this->renderPartial('_myInformationCarerMenu', array('selectedMenu' => 'TypeWork'));
        } else {
            echo Wizard::generateWizard();
        }

        if (Yii::app()->params['test']['showPopulateData'] == true) {
            if ($maintain == false) {
                echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('carer/populateTypeWork'));
            }
        }

        if (Yii::app()->user->hasFlash('success')):
            ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <?php
        endif;
        if (Yii::app()->user->hasFlash('error')):
            ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <?php
        endif;
        if (count($carer->getErrors()) > 0 || count($errors) > 0) {
            ?>   
            <div class="flash-error">
                <?php echo Yii::app()->params['texts']['genericFormErrorMessage']; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="columns large-6 medium-8 small-12">
        <!-- Conditions -->
        <?php
        // $this->renderPartial('/condition/newConditions', array('model' => $carer, 'errors' => $errors, 'index' => -1, 'isClient' => false)); 
        //modified by RC to get rid of the renderPartial which was common between Client and Carers and hence too restrictive,
        $model = $carer;
        $index = -1;
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
        $carerConditions = $model->carerConditions;
        $carerConditionsKeyed = array();

        foreach ($carerConditions as $carerCondition) {

            $carerConditionsKeyed[$carerCondition->id_condition] = true;
        }
        // Activities
        $activities = Condition::getConditions(Condition::TYPE_ACTIVITY);
        ?>

        <h3><?php echo Yii::t('texts', 'HEADER_HELP_PROVIDED_TO_THE_USERS'); ?></h3>

        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>

        <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span></p>
        <?php
        if (isset($errors[$activities_errors])) {
            ?>
            <div id="<?php echo $activities_errors ?>" class="rc-error">
                <?php
                echo $errors[$activities_errors];
                ?>
            </div>
        <?php } ?>

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
        ?>

        <?php
        // Physical Abilities
        $physicalConditions = Condition::getConditions(Condition::TYPE_PHYSICAL);
        ?>


        <h3><?php echo Yii::t('texts', 'HEADER_PHYSICAL_CONDITIONS_OF_SERVICE_USERS'); ?></h3>

        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>


        <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_THE_HIGHEST'); ?><span class="required">*</span></p>

        <?php
        if (isset($errors[$physical_conditions_errors])) {
            ?>

            <div id="<?php echo $physical_conditions_errors ?>" class="rc-error">
                <?php
                echo $errors[$physical_conditions_errors];
                ?>
            </div>
        <?php } ?>

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
        ?>

        <?php
        // Mental Abilities
        $mentalConditions = Condition::getConditions(Condition::TYPE_MENTAL);
        ?>

        <h3><?php echo Yii::t('texts', 'HEADER_MENTAL_CONDITIONS_OF_SERVICE_USERS'); ?></h3>

        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>

        <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_SELECT_THE_HIGHEST'); ?><span class="required">*</span></p>


        <?php if (isset($errors[$mental_conditions_errors])) { ?>

            <div id="<?php echo $mental_conditions_errors ?>" class="rc-error">
                <?php
                echo $errors[$mental_conditions_errors];
                ?>
            </div>

        <?php } ?>

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
        ?>

        <?php if ($model instanceof ServiceUser) { ?>        
            <h3><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_USER'); ?><?php //echo CHtml::activeLabelEx($model, "[$index]note");                                                ?></h3>
            <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_500'); ?></p>
            <div id="<?php //byRC echo $note_errors                                        ?>" class="rc-error">
                <?php echo CHtml::error($model, "[$index]note"); ?>
            </div>
            <?php
            echo CHtml::activeTextArea($model, "[$index]note", array('class' => 'rc-textarea-notes'));
        }
        ?>
        <!-- Gender -->
        <?php
        $labels = $carer->attributeLabels();
        ?>

        <h3><?php echo Yii::t('texts', 'HEADER_GENDER'); ?></h3>
        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>

        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span>
        </p>

        <?php
        if ($carer->getError('work_with_male') !== null) {
            echo $form->error($carer, 'work_with_male');
        }
        ?>


        <div class="rc-container-conditions-tickbox">
            <?php echo $form->checkBox($carer, 'work_with_male'); ?>      
            <?php echo CHtml::label($labels['work_with_male'], 'Carer_work_with_male') ?>
        </div>
        <div class="rc-container-conditions-tickbox">
            <?php echo $form->checkBox($carer, 'work_with_female'); ?>                    
            <?php echo CHtml::label($labels['work_with_female'], 'Carer_work_with_female') ?>
        </div>

        <!-- Age group -->

        <h3><?php echo Yii::t('texts', 'HEADER_AGE_GROUP'); ?></h3>

        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>

        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_SELECT_AT_LEAST_ONE'); ?><span class="required">*</span>
        </p>

        <?php
        if (isset($errors['age_group_errors'])) {
            ?>
            <div id="age_group_errors" class="rc-error">
                <?php
                echo $errors['age_group_errors'];
                ?>
            </div>
        <?php } ?>

        <?php
        $carerAgeGroups = $carer->ageGroups;
        $carerAgeGroupsKeyed = array();
        foreach ($carerAgeGroups as $carerAgeGroup) {
            $carerAgeGroupsKeyed[$carerAgeGroup->age_group] = true;
        }
        $ageGroups = AgeGroup::getAgeGroups();
        $ageGroupsKeys = array_keys($ageGroups);
        foreach ($ageGroupsKeys as $ageGroupsKey) {
            $value = isset($carerAgeGroupsKeyed[$ageGroupsKey]);
            ?>
            <div class="rc-container-conditions-tickbox">
                <?php
                echo CHtml::CheckBox('ageGroup_' . $ageGroupsKey, $value) . "\n";
                echo CHtml::label($ageGroups[$ageGroupsKey], 'ageGroup_' . $ageGroupsKey);
                ?>
            </div>        
            <?php
        }
        ?>

        <!-- Type mission -->
        <h3><?php echo Yii::t('texts', 'HEADER_TYPE_OF_MISSION'); ?></h3>
        <?php
        if ($maintain) {
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainTypeWork', 'navigation' => 'save')));
        }
        ?>

        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_ACCEPT_AT_LEAST_ONE_TYPE'); ?>
        </p>
        <?php
        if (isset($errors['mission_type_errors'])) {
            ?>

            <div id="mission_type_errors" class="rc-error">
                <?php
                echo $errors['mission_type_errors'];
                ?>
            </div>
        <?php } ?>

        <?php echo $form->error($carer, 'live_in', array('class' => 'rc-error')); ?>

        <?php
        if ($carer->live_in == 2) {
            $cssLiveIn = ' style="display:block"';
        } else {
            $cssLiveIn = ' style="display:none"';
        }
        ?>
        <?php echo $form->labelEx($carer, 'live_in'); ?>
        <?php echo $form->radioButtonList($carer, 'live_in', array(2 => Yii::t('texts', 'LABEL_YES'), 1 => Yii::t('texts', 'LABEL_NO'))); ?>
        <div id="live_in_work_radius" <?php echo $cssLiveIn ?> >
            <?php echo $form->error($carer, 'live_in_work_radius', array('class' => 'rc-error')); ?>
            <div class="row collapse">
                <label>How far from your home are you willing to travel ?</label>
                <div class="small-9 columns">
                    <?php echo $form->textField($carer, 'live_in_work_radius', array('maxlength' => 3, 'class' => 'rc-field-small')); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix">Miles</span>
                </div>
            </div>
        </div>
        <?php echo $form->error($carer, 'hourly_work', array('class' => 'rc-error')); ?>
        <?php
        if ($carer->hourly_work == 2) {
            $cssHourlyWork = ' style="display:block"';
        } else {
            $cssHourlyWork = ' style="display:none"';
        }
        ?>
        <hr>
        <?php echo $form->labelEx($carer, 'hourly_work'); ?>
        <?php echo $form->radioButtonList($carer, 'hourly_work', array(2 => Yii::t('texts', 'LABEL_YES'), 1 => Yii::t('texts', 'LABEL_NO'))); ?>
        <div id="hourly_work_radius" <?php echo $cssHourlyWork ?> >
            <?php echo $form->error($carer, 'hourly_work_radius', array('class' => 'rc-error')); ?>
            <div class="row collapse">
                <label>How far from your home are you willing to travel ?</label>
                <div class="small-9 columns">
                    <?php echo $form->textField($carer, 'hourly_work_radius', array('maxlength' => 3, 'class' => 'rc-field-small')); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix">Miles</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="columns large-12 medium-12 small-12">
        <div id="hourly_work_schedule" <?php echo $cssHourlyWork ?> >
            <p class="rc-note">
                <?php echo Yii::t('texts', 'NOTE_TICK_THE_BOX_FOR_EACH_SET'); ?>
            </p>
            <?php
            if (isset($errors['time_slot_errors'])) {
                ?>
                <div id="time_slot_errors" class="rc-error">
                    <?php
                    echo $errors['time_slot_errors'];
                    ?>
                </div>
                <?php
            }
            ?>
            <table>
                <tr>
                    <?php
                    $daysWeeks = CarerAvailability::getDaysWeeks();
                    foreach ($daysWeeks as $daysWeek) {
                        ?>
                        <th class="rc-header-calendar"><?php echo $daysWeek; ?></th>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                $availabilities = $carer->carerAvailabilities;
                $daysWeeksKeys = array_keys($daysWeeks);
                $timeSlots = CarerAvailability::getTimeSlots();
                $timeSlotsKeys = array_keys($timeSlots);
                foreach ($timeSlotsKeys as $timeSlotsKey) {
                    ?>
                    <tr>
                        <?php
                        foreach ($daysWeeksKeys as $daysWeeksKey) {
                            $value = false;
                            foreach ($availabilities as $availability) {
                                if ($availability->time_slot == $timeSlotsKey && $availability->day_week == $daysWeeksKey) {
                                    $value = true;
                                    break;
                                }
                            }
                            ?>
                            <td class="rc-cell-calendar">
                                <?php
                                //NEW, if display (not save after POST) and no data saved yet, everything is ticked
                                if ($display && count($availabilities) == 0) {
                                    $value = true;
                                }

                                $checkBoxName = 'timeSlot_' . $daysWeeksKey . '_' . $timeSlotsKey;
                                echo CHtml::CheckBox($checkBoxName, $value);
                                echo CHtml::label($timeSlots[$timeSlotsKey], $checkBoxName);
                                ?>
                            </td>
                            <?php
                        }
                        ?>  
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <?php
        if ($maintain == false) {
            ?>
            <div class="row">
                <div class="buttons">
                    <div class="columns large-6 medium-6 small-12">
                        <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'button expand', 'submit' => array('carer/typeWork', 'navigation' => 'back'))); ?>
                    </div>
                    <div class="columns large-6 medium-6 small-12">
                        <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_NEXT'), array('class' => 'button expand alert', 'submit' => array('carer/typeWork', 'navigation' => 'next'))); ?>
                    </div>
                </div>
                <?php $this->renderPartial('/common/_ajaxLoader'); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php $this->endWidget(); ?>