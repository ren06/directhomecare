<?php
/* @var $this AdminController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Carers',
);

$this->menu=array(
	array('label'=>'Create Carer', 'url'=>array('create')),
	array('label'=>'Manage Carer', 'url'=>array('admin')),
);
?>

<h1>Carers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'/carer/_view',
)); ?>
