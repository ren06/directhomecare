<?php
/* @var $this AdminController */
/* @var $model Carer */

$this->breadcrumbs=array(
	'Carers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Carer', 'url'=>array('index')),
	array('label'=>'Manage Carer', 'url'=>array('admin')),
);
?>

<h1>Create Carer</h1>

<?php echo $this->renderPartial('/carer/_form', array('model'=>$model)); ?>