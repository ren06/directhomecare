<?php
/* @var $this LiveInMissionController */
/* @var $model Mission */

$this->breadcrumbs = array(
    'Live-in shifts' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List shift', 'url' => array('index')),
    array('label' => 'Create shift', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('live-in-mission-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage shifts</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('/mission/_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'live-in-mission-grid',
    'dataProvider' => $model->getCancelledByCarerMission(),
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->start_date);
            },
            'header' => 'Start',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->end_date);
            },
            'header' => 'End',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->start_date);
            },
            'header' => 'Actual Start',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->end_date);
            },
            'header' => 'Actual End',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getCompletionStatus();
            },
            'header' => 'Completion',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = LiveInMission::getStatusLabel($data->status);
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Status',
            'name' => 'status',
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
