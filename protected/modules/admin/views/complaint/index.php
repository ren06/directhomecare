<?php
/* @var $this ComplaintController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Client Complaints',
);

$this->menu=array(
	array('label'=>'Create Complaint', 'url'=>array('create')),
	array('label'=>'Manage Complaint', 'url'=>array('admin')),
);
?>

<h1>Client Complaints</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
