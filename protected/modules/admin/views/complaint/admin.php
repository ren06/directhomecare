<?php
/* @var $this ComplaintController */
/* @var $model Complaint */

$this->breadcrumbs = array(
    'Client Complaints' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List Complaint', 'url' => array('index')),
    array('label' => 'Create Complaint', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('client-complaint-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Client Complaints</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'client-complaint-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'id_client',
        'id_carer',
        'id_mission',
        array(
            'name' => 'created_by',
            'type' => 'raw',
            'value' => function($data, $row) {
                return $data->getCreatedByLabel();
            },
        ),
        array(
            'name' => 'type',
            'type' => 'raw',
            'value' => function($data, $row) {
                return $data->getTypeLabel();
            },
        ),
        array(
            'name' => 'solved',
            'type' => 'raw',
            'value' => function($data, $row) {
                return $data->getSolvedLabel();
            },
        ),
        array(
            'name' => 'created',
            'type' => 'raw',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->created);
            },
        ),
        /*
          'created',
          'modified',
         */
        array(
            'class' => 'CButtonColumn', // can be omitted, default
            'header' => 'See messages',
            'template' => '{view}',
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('texts', 'View'), //Text label of the button.
                    'url' => 'Yii::app()->createUrl("admin/Complaint/viewMessages", array("id"=>$data->id))',
                    'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS'), 'class' => 'rc-linkbutton-small'),
                    'visible' => 'true',
                ),
            ),
        ),
    ),
));
?>
