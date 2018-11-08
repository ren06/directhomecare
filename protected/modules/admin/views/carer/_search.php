<?php
/* @var $this AdminController */
/* @var $model Carer */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <?php echo $form->label($model, 'id'); ?>
        <?php echo $form->textField($model, 'id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'first_name'); ?>
        <?php echo $form->textField($model, 'first_name', array('size' => 50, 'maxlength' => 50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'last_name'); ?>
        <?php echo $form->textField($model, 'last_name', array('size' => 50, 'maxlength' => 50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'email_address'); ?>
        <?php echo $form->textField($model, 'email_address', array('size' => 60, 'maxlength' => 60)); ?>
    </div>

    <!--	<div class="row">
    <?php echo $form->label($model, 'date_birth'); ?>
    <?php echo $form->textField($model, 'date_birth'); ?>
            </div>-->

    <!--	<div class="row">
    <?php echo $form->label($model, 'gender'); ?>
    <?php echo $form->textField($model, 'gender'); ?>
            </div>-->

    <div class="row">
        <?php echo $form->label($model, 'hourly_work'); ?>
        <?php echo $form->textField($model, 'hourly_work'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'nationality'); ?>
        <?php echo $form->textField($model, 'nationality', array('size' => 50, 'maxlength' => 50)); ?>
    </div>

    <!--	<div class="row">
    <?php echo $form->label($model, 'country_birth'); ?>
    <?php echo $form->textField($model, 'country_birth', array('size' => 50, 'maxlength' => 50)); ?>
            </div>-->

    <?php echo $form->label($model, 'mobile_phone'); ?>
    <?php echo $form->textField($model, 'mobile_phone', array('size' => 15, 'maxlength' => 15)); ?>

    <div class="row">
        <?php echo $form->label($model, 'live_in'); ?>
        <?php echo $form->textField($model, 'live_in'); ?>
    </div>

    <!--	<div class="row">
    <?php echo $form->label($model, 'live_in_work_radius'); ?>
    <?php echo $form->textField($model, 'live_in_work_radius'); ?>
            </div>
    
            <div class="row">
    <?php echo $form->label($model, 'hourly_work_radius'); ?>
    <?php echo $form->textField($model, 'hourly_work_radius'); ?>
            </div>-->

    <!--	<div class="row">
    <?php echo $form->label($model, 'work_with_male'); ?>
    <?php echo $form->textField($model, 'work_with_male'); ?>
            </div>
    
            <div class="row">
    <?php echo $form->label($model, 'work_with_female'); ?>
    <?php echo $form->textField($model, 'work_with_female'); ?>
            </div>-->


    <!--	<div class="row">
    <?php echo $form->label($model, 'car_owner'); ?>
    <?php echo $form->textField($model, 'car_owner'); ?>
            </div>-->


    <!--	<div class="row">
    <?php echo $form->label($model, 'account_number'); ?>
    <?php echo $form->textField($model, 'account_number', array('size' => 20, 'maxlength' => 20)); ?>
            </div>-->


    <div class="row">
        <?php echo $form->label($model, 'wizard_completed'); ?>
        <?php echo $form->textField($model, 'wizard_completed'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'active'); ?>
        <?php echo $form->textField($model, 'active'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->