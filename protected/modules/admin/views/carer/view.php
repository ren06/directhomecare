<?php

if (!empty($asDialog)):
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
        'id'=>'dlg-address-view-'. $model->id,
        'options'=>array(
            'title'=>'View Carer #'. $model->id,
            'autoOpen'=>true,
            'modal'=>false,
            'width'=>550,
           // 'height'=>470,
        ),
 ));
 
else:


/* @var $this AdminController */
/* @var $model Carer */

$this->breadcrumbs=array(
	'Carers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Carer', 'url'=>array('index')),
	array('label'=>'Create Carer', 'url'=>array('create')),
	array('label'=>'Update Carer', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Carer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Carer', 'url'=>array('admin')),
);
?>


<h1>View Carer #<?php echo $model->id; ?></h1>

<?php

?>
<?php endif; ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'first_name',
		'last_name',
		'email_address',
		//'password',
		'date_birth',
		'gender',
		'hourly_work',
		'nationality',
		'country_birth',
		'mobile_phone',
		'live_in',
		'live_in_work_radius',
		'hourly_work_radius',
		'work_with_male',
		'work_with_female',
		'car_owner',
		'motivation_text',
		'personal_text',
		'dh_rating',
		'sort_code',
		'account_number',
		'wizard_completed',
		'terms_conditions',
		'motivation_text_status',
		'personal_text_status',
                'legally_work',
                'terms_conditions',
	),
)); ?>

<?php 
  //----------------------- close the CJuiDialog widget ------------
  if (!empty($asDialog)) $this->endWidget();
?>