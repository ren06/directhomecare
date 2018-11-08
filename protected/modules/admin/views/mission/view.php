<?php
/* @var $this LiveInMissionController */
/* @var $model LiveInMission */

$this->breadcrumbs=array(
	'Live-in shifts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List live-in shift', 'url'=>array('index')),
	array('label'=>'Create live-in shift', 'url'=>array('create')),
	array('label'=>'Update live-in shift', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete live-in shift', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage live-in shift', 'url'=>array('admin')),
);
?>

<h1>View live-in shift #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_request',
		'start_date',
		'end_date',
		'start_date',
		'end_date',
		'status',
		'created',
		'modified',
		'status',
	),
)); ?>