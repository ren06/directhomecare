<?php
/* @var $this AdminController */
/* @var $model Carer */

$this->breadcrumbs=array(
	'Carers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Carer', 'url'=>array('index')),
	array('label'=>'Create Carer', 'url'=>array('create')),
	array('label'=>'View Carer', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Carer', 'url'=>array('admin')),
);
?>

<h1>Update Carer <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('/carer/_form', array('model'=>$model)); ?>