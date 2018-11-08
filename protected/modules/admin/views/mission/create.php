<?php
/* @var $this LiveInMissionController */
/* @var $model Mission */

$this->breadcrumbs=array(
	'Live-in shiftMissions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List shift', 'url'=>array('index')),
	array('label'=>'Manage shift', 'url'=>array('admin')),
);
?>

<h1>Create shift</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>