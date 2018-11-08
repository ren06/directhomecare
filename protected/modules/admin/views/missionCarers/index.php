<?php
/* @var $this LiveInMissionCarersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Live-in shift carers',
);

$this->menu=array(
	array('label'=>'Create MissionCarers', 'url'=>array('create')),
	array('label'=>'Manage MissionCarers', 'url'=>array('admin')),
);
?>

<h1>Live-in shift carers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>