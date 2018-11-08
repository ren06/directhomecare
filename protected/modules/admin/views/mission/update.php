<?php
/* @var $this LiveInMissionController */
/* @var $model LiveInMission */

$this->breadcrumbs = array(
    'Live-in shifts' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List live-in shift', 'url' => array('index')),
    array('label' => 'Create live-in shift', 'url' => array('create')),
    array('label' => 'View live-in shift', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage live-in shift', 'url' => array('admin')),
);
?>

<h1>Update Shift <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('/mission/_form', array('model' => $model)); ?>

<h2>Copy with new dates</h2>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'live-in-mission-copy-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'start_date'); ?>
        <?php echo $form->textField($model, 'start_date_time'); ?>
        <?php echo $form->error($model, 'start_date'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'end_date'); ?>
        <?php echo $form->textField($model, 'end_date_time'); ?>
        <?php echo $form->error($model, 'end_date'); ?>
    </div>
    <?php
    //echo $model->id; 
    echo CHtml::activeHiddenField($model, 'id');
    ?>
    <div class="row buttons">
        <?php echo CHtml::button('Copy', array('submit' => array('/admin/missionAdmin/copyMission'))); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->