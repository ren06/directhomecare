<?php

$this->breadcrumbs=array(
	'Live-in shift carers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List shift carers', 'url'=>array('index')),
	array('label'=>'Create shift carers', 'url'=>array('create')),
	array('label'=>'Update shift carers', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete shift carers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage shift carers', 'url'=>array('admin')),
);
?>

<h1>View shift carers #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                'id',
		'id_mission',
		'id_applying_carer',
		'status',
		
	),
)); ?>