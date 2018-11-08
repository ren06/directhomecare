
<?php
/* @var $this MissionController */
/* @var $model Mission */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'mission-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="rc-note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'id_address'); ?>
        <?php echo $form->textField($model,'id_address', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'id_address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'id_booking'); ?>
        <?php echo $form->textField($model,'id_booking', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'id_booking'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'id_mission_payment'); ?>
        <?php echo $form->textField($model,'id_mission_payment', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'id_mission_payment'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->textField($model,'type', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'start_date_time'); ?>
        <?php echo $form->textField($model,'start_date_time'); ?>
        <?php echo $form->error($model,'start_date_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'end_date_time'); ?>
        <?php echo $form->textField($model,'end_date_time'); ?>
        <?php echo $form->error($model,'end_date_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'discarded_by_carer'); ?>
        <?php echo $form->textField($model,'discarded_by_carer'); ?>
        <?php echo $form->error($model,'discarded_by_carer'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'discarded_by_client'); ?>
        <?php echo $form->textField($model,'discarded_by_client'); ?>
        <?php echo $form->error($model,'discarded_by_client'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'carer_credited'); ?>
        <?php echo $form->textField($model,'carer_credited'); ?>
        <?php echo $form->error($model,'carer_credited'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'cancel_by_client_date'); ?>
        <?php echo $form->textField($model,'cancel_by_client_date'); ?>
        <?php echo $form->error($model,'cancel_by_client_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'created'); ?>
        <?php echo $form->textField($model,'created', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'created'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'modified'); ?>
        <?php echo $form->textField($model,'modified', array('disabled' => 'disabled')); ?>
        <?php echo $form->error($model,'modified'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
