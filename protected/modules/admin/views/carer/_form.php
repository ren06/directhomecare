<?php
/* @var $this CarerController */
/* @var $model Carer */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'carer-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="rc-note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'id_address'); ?>
        <?php echo $form->textField($model, 'id_address', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'id_address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'first_name'); ?>
        <?php echo $form->textField($model, 'first_name', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'last_name'); ?>
        <?php echo $form->textField($model, 'last_name', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email_address'); ?>
        <?php echo $form->textField($model, 'email_address', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->error($model, 'email_address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'date_birth'); ?>
        <?php echo $form->textField($model, 'date_birth'); ?>
        <?php echo $form->error($model, 'date_birth'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'gender'); ?>
        <?php echo $form->textField($model, 'gender'); ?>
        <?php echo $form->error($model, 'gender'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'hourly_work'); ?>
        <?php echo $form->textField($model, 'hourly_work'); ?>
        <?php echo $form->error($model, 'hourly_work'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'nationality'); ?>
        <?php echo $form->textField($model, 'nationality', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->error($model, 'nationality'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'mobile_phone'); ?>
        <?php echo $form->textField($model, 'mobile_phone', array('size' => 15, 'maxlength' => 15)); ?>
        <?php echo $form->error($model, 'mobile_phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'live_in'); ?>
        <?php echo $form->textField($model, 'live_in'); ?>
        <?php echo $form->error($model, 'live_in'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'live_in_work_radius'); ?>
        <?php echo $form->textField($model, 'live_in_work_radius'); ?>
        <?php echo $form->error($model, 'live_in_work_radius'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'hourly_work_radius'); ?>
        <?php echo $form->textField($model, 'hourly_work_radius'); ?>
        <?php echo $form->error($model, 'hourly_work_radius'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'work_with_male'); ?>
        <?php echo $form->textField($model, 'work_with_male'); ?>
        <?php echo $form->error($model, 'work_with_male'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'work_with_female'); ?>
        <?php echo $form->textField($model, 'work_with_female'); ?>
        <?php echo $form->error($model, 'work_with_female'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'driving_licence'); ?>
        <?php echo $form->textField($model, 'driving_licence'); ?>
        <?php echo $form->error($model, 'driving_licence'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'car_owner'); ?>
        <?php echo $form->textField($model, 'car_owner'); ?>
        <?php echo $form->error($model, 'car_owner'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'last_login_date'); ?>
        <?php echo $form->textField($model, 'last_login_date'); ?>
        <?php echo $form->error($model, 'last_login_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'account_number'); ?>
        <?php echo $form->textField($model, 'account_number', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->error($model, 'account_number'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'sort_code'); ?>
        <?php echo $form->textField($model, 'sort_code', array('size' => 10, 'maxlength' => 10)); ?>
        <?php echo $form->error($model, 'sort_code'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'wizard_completed'); ?>
        <?php echo $form->textField($model, 'wizard_completed'); ?>
        <?php echo $form->error($model, 'wizard_completed'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'modified'); ?>
        <?php echo $form->textField($model, 'modified'); ?>
        <?php echo $form->error($model, 'modified'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'created'); ?>
        <?php echo $form->textField($model, 'created'); ?>
        <?php echo $form->error($model, 'created'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'active'); ?>
        <?php echo $form->textField($model, 'active'); ?>
        <?php echo $form->error($model, 'active'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'overall_score'); ?>
        <?php echo $form->textField($model, 'overall_score'); ?>
        <?php echo $form->error($model, 'overall_score'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'overall_rating'); ?>
        <?php echo $form->textField($model, 'overall_rating'); ?>
        <?php echo $form->error($model, 'overall_rating'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'deactivated'); ?>
        <?php echo $form->textField($model, 'deactivated'); ?>
        <?php echo $form->error($model, 'deactivated'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'no_job_alerts'); ?>
        <?php echo $form->textField($model, 'no_job_alerts'); ?>
        <?php echo $form->error($model, 'no_job_alerts'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->