

<?php
/* @var $this LoginHistoryCarerController */
/* @var $model LoginHistoryCarer */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id'); ?>
        <?php echo $form->textField($model,'id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'id_carer'); ?>
        <?php echo $form->textField($model,'id_carer'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'login_date_time'); ?>
        <?php echo $form->textField($model,'login_date_time'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
