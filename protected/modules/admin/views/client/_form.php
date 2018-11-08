<?php
/* @var $this ClientAdminController */
/* @var $model Client */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'client-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="rc-note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

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
        <?php echo $form->textField($model, 'email_address', array('size' => 60, 'maxlength' => 80)); ?>
        <?php echo $form->error($model, 'email_address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'mobile_phone'); ?>
        <?php echo $form->textField($model, 'mobile_phone', array('size' => 15, 'maxlength' => 15)); ?>
        <?php echo $form->error($model, 'mobile_phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'wizard_completed'); ?>
        <?php echo $form->textField($model, 'wizard_completed'); ?>
        <?php echo $form->error($model, 'wizard_completed'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'no_newsletter'); ?>
        <?php echo $form->textField($model, 'no_newsletter'); ?>
        <?php echo $form->error($model, 'no_newsletter'); ?>
    </div>

    <div class="row">
         <?php echo $form->labelEx($model, 'terms_conditions'); ?>
        <?php echo $form->textField($model, 'terms_conditions'); ?>
        <?php echo $form->error($model, 'terms_conditions'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'created'); ?>
        <?php echo $form->textField($model, 'created', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'created'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'modified'); ?>
        <?php echo $form->textField($model, 'modified', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model, 'modified'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->