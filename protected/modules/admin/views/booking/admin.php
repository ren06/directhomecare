<?php
$this->breadcrumbs = array(
    'Bookings' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List Bookings', 'url' => array('index')),
    array('label' => 'Create Booking', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('booking-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Bookings</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('/booking/_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
        ));

$dataProvider = $model->search();
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'booking-grid',
    'dataProvider' => $model->searchPerClient(),
    'filter' => $model,
    'columns' => array(
        array(
            'id' => 'autoId',
            'class' => 'CCheckBoxColumn',
            'selectableRows' => '50',
        ),
        'id',
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

                $value = CHtml::link($data->id_client, array("clientAdmin/viewClient", "id" => $data->id_client));
                return $value . '<br>' . $data->client->fullName . '<br>' . $data->client->mobile_phone;
            },
            'header' => 'Client',
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'name' => 'id_client',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getServiceLocation()->display(",")',
            'header' => 'Location',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->startDateTime',
            'header' => 'Start',
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'name' => 'start_date_time',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->endDateTime',
            'header' => 'End',
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'name' => 'end_date_time',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getVisibilityLabel()',
            'header' => 'Discarded',
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'name' => 'discarded_by_client',
            'type' => 'raw',
        ),
        'created',
        'modified',
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'label' => 'VIEW',
                    'url' => 'Yii::app()->createUrl("/admin/bookingAdmin/editBooking", array("id"=>$data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small'),
                ),
            ),
        ),
    ),
));
?>

Set selected row(s) to:
<div class="row">
    <?php echo CHtml::dropDownList('discarded', '', Booking::getVisibilityptions()); ?>	
</div>
<div class="row buttons">
    <?php echo CHtml::button('Change discarded', array('submit' => array('/admin/bookingAdmin/updateDiscarded'))); ?>
</div>

<?php $this->endWidget(); ?>