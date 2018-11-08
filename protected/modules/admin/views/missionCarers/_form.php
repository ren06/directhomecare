
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'live-in-mission-carers-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="rc-note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model);
        
         $htmlOptions = array('disabled' => 'disabled');
        ?>
              
	<div class="row" style>
		<?php echo $form->labelEx($model,'id_mission'); ?>
		<?php echo $form->textField($model,'id_mission', $htmlOptions); ?>
		<?php echo $form->error($model,'id_mission'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_applying_carer'); ?>
		<?php echo $form->textField($model,'id_applying_carer', $htmlOptions); ?>
		<?php echo $form->error($model,'id_applying_carer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusOptions()); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->