<style type="text/css">
    #myinformation {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_DETAILS');
$this->pageSubTitle = 'Introduce yourself';
?>


<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'carer_details-form',
    'enableAjaxValidation' => false,
    'focus' => array($carer, 'emailAddress'),
    'stateful' => true,
        ));
?>

<div class="row">    
    <div class="columns large-12 medium-12 small-12">
        <?php
        if ($maintain == true) {
            $this->renderPartial('_myInformationCarerMenu', array('selectedMenu' => 'Details'));
        } else {
            echo Wizard::generateWizard();
        }
        ?>

        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
            <?php
        endif;
        if (Yii::app()->user->hasFlash('error')):
            ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('error'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="columns large-6 medium-8 small-12">
        <?php
        if ($maintain) {
            echo '<div class="rc-module-container-button">' . CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainDetails', 'navigation' => 'save'))) . '</div>';
        }
        ?>

        <?php
        if (Yii::app()->params['test']['showPopulateData'] == true) {
            if ($maintain == false) {
                echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('carer/populate'));
            }
        }
        ?>

        <h4>Name and Email</h4>

        <?php
        $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field', 'placeholder' => 'Email address');
        ?>

        <?php echo $form->textField($carer, 'email_address', $htmlOptions, array()); ?>
        <?php echo $form->error($carer, 'email_address', array('class' => 'rc-error')); ?>

        <?php
        if (Yii::app()->user->isGuest == false) {
            $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field-disabled', 'disabled' => 'disabled');
        }
        // }
        ?>
        <?php
        $htmlOptions = array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'First name');
        if (!$carer->isNameEditable()) {
            $htmlOptions['disabled'] = 'disabled';
            $htmlOptions['class'] = 'rc-field-disabled';
        }
        ?>
        <?php echo $form->textField($carer, 'first_name', $htmlOptions); ?>
        <?php echo $form->error($carer, 'first_name', array('class' => 'rc-error')); ?>

        <?php
        $htmlOptions = array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'Last name');
        if (!$carer->isNameEditable()) {
            $htmlOptions['disabled'] = 'disabled';
            $htmlOptions['class'] = 'rc-field-disabled';
        }
        ?>
        <?php echo $form->textField($carer, 'last_name', $htmlOptions); ?>
        <?php echo $form->error($carer, 'last_name', array('class' => 'rc-error')); ?>

        <?php
        Yii::import('application.helpers.Countries');
        $countries = Countries::getCountries();
        $htmlOptions = array();
        $htmlOptions['class'] = 'rc-drop-long';
        Yii::import('application.helpers.Nationalities');
        $nationalities = Nationalities::getNationalities();
        ?>
        <!--
        <?php echo $form->labelEx($carer, 'country_birth'); ?>
        <?php echo $form->dropDownList($carer, 'country_birth', $countries, $htmlOptions); ?>
        <?php echo $form->error($carer, 'country_birth', array('class' => 'rc-error')); ?>
        -->

        <?php
        $widgetHtmlOptions = array('class' => 'rc-drop');

        if (!$carer->isBirthDateEditable()) {
            $widgetHtmlOptions['disabled'] = 'disabled';
            $widgetHtmlOptions['class'] = 'rc-drop-disabled';
        }
        ?>
        <br>
        <h4>Date of birth</h4>
        <?php $this->widget('DropDownDatePickerWidget', array('myLocale' => 'en_gb', 'date' => $carer->date_birth, 'htmlOptions' => $widgetHtmlOptions)); ?>
        <?php echo $form->error($carer, 'date_birth', array('class' => 'rc-error')); ?>
        
        <br/>
        <h4>Nationality</h4>
        <?php
        $htmlOptions['placeholder'] = 'Nationality';
        if (!$carer->isNameEditable()) {
            $htmlOptions['disabled'] = 'disabled';
            $htmlOptions['class'] = 'rc-drop-long-disabled';
        } else {
            $htmlOptions['class'] = 'rc-drop-long';
        }
        ?>

        <?php echo $form->dropDownList($carer, 'nationality', $nationalities, $htmlOptions); ?>
        <?php echo $form->error($carer, 'nationality', array('class' => 'rc-error')); ?>

        <br/>
        <br/>
        <h4>Address</h4>

        <?php echo $form->textField($address, 'address_line_1', array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'Address line 1')); ?>
        <?php echo $form->error($address, 'address_line_1', array('class' => 'rc-error')); ?>

        <?php echo $form->textField($address, 'address_line_2', array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'Address line 2')); ?>
        <?php echo $form->error($address, 'address_line_2', array('class' => 'rc-error')); ?>

        <?php echo $form->textField($address, 'city', array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'City')); ?>
        <?php echo $form->error($address, 'city', array('class' => 'rc-error')); ?>

        <?php echo $form->textField($address, 'post_code', array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'Post code')); ?>
        <?php echo $form->error($address, 'post_code', array('class' => 'rc-error')); ?>

        <br/>
        <h4>Mobile Phone</h4>


        <?php echo $form->textField($carer, 'mobile_phone', array('maxlength' => 50, 'class' => 'rc-field', 'placeholder' => 'Mobile phone')); ?>
        <?php echo $form->error($carer, 'mobile_phone', array('class' => 'rc-error')); ?>

        <?php
        $htmlOptions = array('separator' => Yii::app()->params['radioButtonSeparator']);
        if (!$carer->isGenderEditable()) {
            $htmlOptions['disabled'] = 'disabled';
            //$htmlOptions['class'] = 'rc-field-disabled';
        }
        ?>
        <br>
        <h4>Your gender</h4>
        <?php echo $form->radioButtonList($carer, 'gender', Carer::$genderLabels, $htmlOptions); ?>
        <?php echo $form->error($carer, 'gender', array('class' => 'rc-error')); ?>
        <?php
        $scenario = $carer->getScenario();
        if ($scenario == Carer::SCENARIO_CREATE_CARER) {
            $htmlOptions = array();
            $setHidden = false;
        } else {
            $htmlOptions = array('disabled' => 'disabled');
            $setHidden = true;
        }
        ?>

        <br/>
        <br/>
        <h4>Terms and Conditions</h4>

        <?php echo $form->labelEx($carer, 'terms_conditions'); ?>
        <?php
        if ($setHidden) {
            echo CHtml::hiddenField('terms_conditions', 1, array('name' => 'terms_conditions'));
        }
        $val = $carer->terms_conditions;
        echo $form->checkBox($carer, 'terms_conditions', $htmlOptions);
        echo CHtml::label(Yii::t('texts', 'NOTE_I_HAVE_READ_AND_ACCEPT_THE'), 'Carer_terms_conditions');
        ?>
        <a target="_blank" class="rc-link" href="<?php echo Yii::app()->request->baseUrl; ?>/terms"><?php echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS'); ?></a>.
        <?php echo $form->error($carer, 'terms_conditions', array('class' => 'rc-error')); ?>

        <?php echo $form->labelEx($carer, 'legally_work'); ?>
        <?php
        if ($setHidden) {
            echo CHtml::hiddenField('legally_work', 1, array('name' => 'legally_work'));
        }
        echo $form->checkBox($carer, 'legally_work', $htmlOptions);
        echo CHtml::label(Yii::t('texts', 'NOTE_I_AM_LEGALLY_ALLOWED_TO_WORK'), 'Carer_legally_work');
        echo $form->error($carer, 'legally_work', array('class' => 'rc-error'));
        ?>
        
        <?php
$this->endWidget();

?>

        <?php
        if ($maintain == true) {
            $resetPasswordForm = $this->beginWidget('CActiveForm', array(
                'id' => 'resetpassword-form',
                //'enableClientValidation' => true,
                'clientOptions' => array(
                // 'validateOnSubmit' => true,
                ),
            ));
            ?>
            <br>
            <br>
            <h4>Change password</h4>
           
            <div id="resetPassword">
                <?php echo $resetPasswordForm->labelEx($resetPassword, 'oldPassword'); ?>
                <?php echo $resetPasswordForm->passwordField($resetPassword, 'oldPassword', array('maxlength' => 50, 'class' => 'rc-field', 'autocomplete' => 'off')); ?>
                <?php echo $resetPasswordForm->error($resetPassword, 'oldPassword', array('class' => 'rc-error')); ?>

                <?php echo $resetPasswordForm->labelEx($resetPassword, 'newPassword'); ?>
                <?php echo $resetPasswordForm->passwordField($resetPassword, 'newPassword', array('maxlength' => 50, 'class' => 'rc-field', 'autocomplete' => 'off')); ?>
                <?php echo $resetPasswordForm->error($resetPassword, 'newPassword', array('class' => 'rc-error')); ?>

                <?php echo $resetPasswordForm->labelEx($resetPassword, 'newPasswordRepeat'); ?>
                <?php echo $resetPasswordForm->passwordField($resetPassword, 'newPasswordRepeat', array('maxlength' => 50, 'class' => 'rc-field', 'autocomplete' => 'off')); ?>
                <?php echo $resetPasswordForm->error($resetPassword, 'newPasswordRepeat', array('class' => 'rc-error')); ?>
            </div>
             <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CHANGE_PASSWORD'), array('class' => 'button small', 'submit' => array('carer/maintainPassword'))); ?>

            <?php
            $this->endWidget();
        }
        if ($maintain == false) {
            ?>
            <div class="buttons">
                <?php
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_NEXT'), array('class' => 'button expand alert', 'style' => 'padding-left:30px; padding-right:30px;', 'submit' => array('carer/details', 'navigation' => 'next')));
                ?>
            </div>  
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        <?php } ?>    
    </div>
</div>
