<?php
/* @var $this ComplaintController */
/* @var $data Complaint */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_client')); ?>:</b>
	<?php echo CHtml::encode($data->id_client); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_carer')); ?>:</b>
	<?php echo CHtml::encode($data->id_carer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_mission')); ?>:</b>
	<?php echo CHtml::encode($data->id_mission); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('solved')); ?>:</b>
	<?php echo CHtml::encode($data->solved); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	*/ ?>

</div>