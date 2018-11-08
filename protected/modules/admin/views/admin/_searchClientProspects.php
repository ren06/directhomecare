<?php
/* @var $this ClientProspectController */
/* @var $model ClientProspect */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'sessionID'); ?>
        <?php echo $form->textField($model,'sessionID',array('size'=>60,'maxlength'=>80)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'email_address_step1'); ?>
        <?php echo $form->textField($model,'email_address_step1',array('size'=>60,'maxlength'=>80)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'email_address_step2'); ?>
        <?php echo $form->textField($model,'email_address_step2',array('size'=>60,'maxlength'=>80)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'created'); ?>
        <?php echo $form->textField($model,'created'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->