<?php
/* @var $this ComplaintController */
/* @var $model Complaint */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'client-complaint-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="rc-note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_client'); ?>
		<?php echo $form->textField($model,'id_client'); ?>
		<?php echo $form->error($model,'id_client'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_carer'); ?>
		<?php echo $form->textField($model,'id_carer'); ?>
		<?php echo $form->error($model,'id_carer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_mission'); ?>
		<?php echo $form->textField($model,'id_mission'); ?>
		<?php echo $form->error($model,'id_mission'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'solved'); ?>
		<?php echo $form->textField($model,'solved'); ?>
		<?php echo $form->error($model,'solved'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified'); ?>
		<?php echo $form->textField($model,'modified'); ?>
		<?php echo $form->error($model,'modified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->