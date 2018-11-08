<?php
/* @var $this ClientAdminController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Clients',
);

$this->menu=array(
	array('label'=>'Create Client', 'url'=>array('create')),
	array('label'=>'Manage Client', 'url'=>array('admin')),
);
?>

<h1>Clients</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'/client/_view',
)); ?>
