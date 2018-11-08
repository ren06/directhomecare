<?php
/* @var $this LiveInMissionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Live-in shifts',
);

$this->menu=array(
	array('label'=>'Create LiveInMission', 'url'=>array('create')),
	array('label'=>'Manage LiveInMission', 'url'=>array('admin')),
);
?>

<h1>Live-in shifts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>