<style type="text/css">
    #postajob{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = 'Carers in your area: ' . $job->post_code;
$this->pageSubTitle = 'Let\'s send them a message !';

$onChangeGender = "js: toggle(this, true);";
?>

<?php
if (count($carers) == 0) {
    echo '<div class="large-12 medium-12 small-12 columns">';
    echo "Sorry we haven't got any carers in your area";
    echo '/<div>';
} else {
    ?>
    <div class="row">
        <?php
        $this->renderPartial('application.views.carer.profile._carerProfileSamplePostJobPage', array('margin' => '0em'));
        ?>
    </div>
    <div class="row">
        <div class="large-6 medium-8 small-12 columns">
            <?php
            if (Yii::app()->params['test']['showPopulateData'] == true) {
                echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('site/populateJobUser'));
            }
            ?>

            </p>
            <?php
            if (Yii::app()->user->isGuest == true) {
                ?>
                <p>
                    Already a user ? <a href="#" data-reveal-id="myModal">Click to sign in.</a>
                </p>
                <?php
            }
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'job-job-form',
                'enableAjaxValidation' => false,
            ));
            echo CHtml::activeHiddenField($job, 'post_code', array('value' => $job->post_code));
            ?>
            <h4>Gender of the carer</h4>
            <?php
            //GENDER
            $attributeNameShowMale = 'gender_carer_male';
            $checkBoxId = $attributeNameShowMale;
            echo CHtml::CheckBox($attributeNameShowMale, ( $job->gender_carer == Constants::GENDER_MALE || $job->gender_carer == Constants::GENDER_BOTH), array('onChange' => $onChangeGender));
            echo CHtml::label('Male', $checkBoxId);
            ?>

            <?php
            $attributeNameShowFemale = 'gender_carer_female';
            $checkBoxId = $attributeNameShowFemale;
            echo CHtml::CheckBox($attributeNameShowFemale, ( $job->gender_carer == Constants::GENDER_FEMALE || $job->gender_carer == Constants::GENDER_BOTH), array('onChange' => $onChangeGender));
            echo CHtml::label('Female', $checkBoxId);
            ?>
            <h4>The carer should provide</h4>
            <?php
            echo $form->error($job, 'formActivities', array('class' => 'rc-error'));
            $activities = Condition::getConditions(Condition::TYPE_ACTIVITY);
            $activitiesValues = $job->formActivities;
            $i = 0;
            foreach ($activities as &$activity) {
                $i++;
                ?>
                <div>
                    <?php
                    $attributeName = 'activities_condition_';
                    $activityId = $activity->id;
                    $attributeName = $attributeName . $activityId;

                    if (in_array($activityId, $activitiesValues)) {
                        $value = true;
                    } else {
                        $value = false;
                    }
                    echo CHtml::checkBox($attributeName, $value, array('id' => $attributeName, 'value' => $activityId, 'onClick' => ''));
                    $text = Condition::getText($activity->name);
                    $tooltip = Condition::getTextTooltip($activity->name);
                    $value = UIServices::renderTooltip($text, $tooltip, '', true);
                    echo CHtml::label($value, $attributeName);
                    ?>
                </div>
                <?php
            }
            ?>
            <h4>Who is the care for ?</h4>
            <?php
            $javascript = 'js:$("#for_other").show("blind", "slow"); js:$("#this_person_is").show("blind", "slow"); js:$("#i_am").hide("blind", "slow");';

            $attributeName = 'Job[who_for]';
            $attributeId = $attributeName . '_' . Constants::FOR_OTHER;

            echo CHtml::radioButton($attributeName, ($job->who_for == Constants::FOR_OTHER), array('id' => $attributeId, 'value' => Constants::FOR_OTHER, 'onClick' => $javascript));
            echo CHtml::label('The care is for a relative', $attributeId, array('onClick' => $javascript));
            echo Yii::app()->params['radioButtonSeparator'];

            $javascript = 'js:$("#for_other").hide("blind", "slow"); js:$("#this_person_is").hide("blind", "slow"); js:$("#i_am").show("blind", "slow");';

            $attributeId = $attributeName . '_' . Constants::FOR_MYSELF;

            echo CHtml::radioButton($attributeName, ($job->who_for == Constants::FOR_MYSELF), array('id' => $attributeId,
                'value' => Constants::FOR_MYSELF, 'onClick' => $javascript));
            echo CHtml::label('The care is for me', $attributeId, array('onClick' => $javascript));
            ?>
            <?php
            if ($job->who_for == Constants::FOR_OTHER) {
                $styleForOther = 'display:visible';
                $styleForMe = 'display:none';
            } else {
                $styleForOther = 'display:none';
                $styleForMe = 'display:visible';
            }
            ?>

            <span id="for_other" style="<?php echo $styleForOther ?>">
                <?php
                echo $form->textField($job, 'first_name_user', array('maxlength' => 50, 'placeholder' => 'First name', 'class' => 'rc-field'));
                echo $form->error($job, 'first_name_user', array('class' => 'rc-error'));
                echo $form->textField($job, 'last_name_user', array('maxlength' => 50, 'placeholder' => 'Last name', 'class' => 'rc-field'));
                echo $form->error($job, 'last_name_user', array('class' => 'rc-error'));
                ?>
            </span>
            <h4 id="this_person_is" style="<?php echo $styleForOther ?>">The gender of the user is </h4>
            <h4 id="i_am" style="<?php echo $styleForMe ?>">My gender is </h4> 
            <?php
            $attributeName = 'Job[gender_user]';
            $attributeId = $attributeName . '_' . Constants::GENDER_FEMALE;
            echo CHtml::radioButton($attributeName, ($job->gender_user == Constants::GENDER_FEMALE), array('id' => $attributeId, 'value' => Constants::GENDER_FEMALE, 'onClick' => ''));
            echo CHtml::label('Female', $attributeId);
            echo Yii::app()->params['radioButtonSeparator'];
            $attributeId = $attributeName . '_' . Constants::GENDER_MALE;
            echo CHtml::radioButton($attributeName, ($job->gender_user == Constants::GENDER_MALE), array('id' => $attributeId, 'value' => Constants::GENDER_MALE, 'onClick' => ''));
            echo CHtml::label('Male', $attributeId);
            ?>
            <h4>Age and condition of the user</h4>
            <?php
            echo $form->dropDownList($job, 'age_group', AgeGroup::getAgeGroups(), array('class' => 'rc-drop'));
            echo $form->dropDownList($job, 'mental_health', Condition::getConditionsForDropdonw(Condition::TYPE_MENTAL), array('class' => 'rc-drop'));
            echo $form->dropDownList($job, 'physical_health', Condition::getConditionsForDropdonw(Condition::TYPE_PHYSICAL), array('class' => 'rc-drop'));
            ?>
            <h4>Enter your message (max 255 characters)</h4>
            <?php
            echo CHtml::activeTextArea($job, "message", array('style' => 'min-height:8em'));
            if (Yii::app()->user->isGuest == true) {

                if (Yii::app()->user->hasFlash('success')):
                    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
                endif;
                if (Yii::app()->user->hasFlash('error')):
                    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
                endif;
                ?>
                <h4>Sign up (it's free)</h4>
                <?php
                echo $form->textField($client, 'email_address', array('maxlength' => 60, 'placeholder' => 'Email address', 'class' => 'rc-field'));
                echo $form->error($client, 'email_address', array('class' => 'rc-error'));

                echo $form->textField($client, 'first_name', array('maxlength' => 50, 'placeholder' => 'First name', 'class' => 'rc-field'));
                echo $form->error($client, 'first_name', array('class' => 'rc-error'));

                echo $form->textField($client, 'last_name', array('maxlength' => 50, 'placeholder' => 'Last name', 'class' => 'rc-field'));
                echo $form->error($client, 'last_name', array('class' => 'rc-error'));

                echo $form->passwordField($client, 'password', array('id' => 'passwordField', 'maxlength' => 40, 'placeholder' => 'Pasword', 'class' => 'rc-field', 'autocomplete' => 'off'));
                echo $form->error($client, 'password', array('class' => 'rc-error'));

                echo $form->passwordField($client, 'repeat_password', array('id' => 'passwordField', 'maxlength' => 40, 'placeholder' => 'Repeat pasword', 'class' => 'rc-field', 'autocomplete' => 'off'));
                echo $form->error($client, 'repeat_password', array('class' => 'rc-error'));

                //Side Cart
                $client = Session::getSigninClient();
                //$this->renderPartial('/client/_sideCart', array('client' => $client));
            }

            //echo CHtml::button(Yii::t('texts', 'BUTTON_BACK'), array('submit' => array('signUp', 'nav' => 'back'), 'class' => 'rc-button-white'));
            //triggered by enter key:
            if (Yii::app()->user->isGuest == true) {
                $label = 'Post this job';
            } else {
                $label = 'Post this job';
            }
            echo CHtml::button($label, array('submit' => array('site/postJob', 'postCode' => $job->post_code), 'class' => 'button expand'));
            ?>
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>

    <?php
}
?>
<script>
    function toggle(object, showMore) {

        var male = $('#gender_carer_male');
        var female = $('#gender_carer_female');

        if (male.prop("checked") === false && female.prop("checked") === false) {

            if (object.id === "gender_carer_male") {

                female.prop("checked", true);
            }
            else {
                male.prop("checked", true);
            }
        }
    }
</script>

