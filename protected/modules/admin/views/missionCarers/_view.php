
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_mission')); ?>:</b>
	<?php echo CHtml::encode($data->id_mission); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_applying_carer')); ?>:</b>
	<?php echo CHtml::encode($data->id_applying_carer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>