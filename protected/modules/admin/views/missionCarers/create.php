<?php

$this->breadcrumbs=array(
	'Live-in shift carers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List shift carers', 'url'=>array('index')),
	array('label'=>'Manage shift carers', 'url'=>array('admin')),
);
?>

<h1>Create shift carers</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>