<?php

$this->breadcrumbs=array(
	'Live-in shift carers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Shift carers', 'url'=>array('index')),
	array('label'=>'Create Shift carers', 'url'=>array('create')),
	array('label'=>'View Shift carers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Shift carers', 'url'=>array('admin')),
);
?>

<h1>Update shift carers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>