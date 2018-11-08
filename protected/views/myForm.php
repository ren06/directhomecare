<?php
/* @var $this BookingHourlyOneDayFormController */
/* @var $model BookingHourlyOneDayForm */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'booking-hourly-one-day-form-myForm-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="rc-note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_hour'); ?>
		<?php echo $form->textField($model,'start_hour'); ?>
		<?php echo $form->error($model,'start_hour'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_minute'); ?>
		<?php echo $form->textField($model,'start_minute'); ?>
		<?php echo $form->error($model,'start_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_hour'); ?>
		<?php echo $form->textField($model,'end_hour'); ?>
		<?php echo $form->error($model,'end_hour'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_minute'); ?>
		<?php echo $form->textField($model,'end_minute'); ?>
		<?php echo $form->error($model,'end_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startHour'); ?>
		<?php echo $form->textField($model,'startHour'); ?>
		<?php echo $form->error($model,'startHour'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->