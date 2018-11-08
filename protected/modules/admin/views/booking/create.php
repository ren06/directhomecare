<?php

$this->breadcrumbs=array(
	'Bookings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Bookings', 'url'=>array('index')),
	array('label'=>'Manage Bookings', 'url'=>array('admin')),
);
?>

<h1>Create Booking</h1>

<?php echo $this->renderPartial('/booking/_form', array('model'=>$model)); ?>