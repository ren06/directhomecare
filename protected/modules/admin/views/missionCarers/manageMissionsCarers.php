<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('live-in-mission-carers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Shift - carers associations - TESTING ONLY! Don't use the following in production!</h1>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
<?php endif ?>


<?php echo CHtml::link('Advanced search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
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
    'id' => 'live-in-mission-carers-grid',
    'pager' => 'LinkPager',
    'selectableRows' => 2,
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => array(
        array(
            'id' => 'autoId',
            'class' => 'CCheckBoxColumn',
            'selectableRows' => '50',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->id',
            'header' => 'ID',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => 'CHtml::link($data->id_mission, array("missionAdmin/view", "id"=>$data->id_mission))',
            'header' => 'Mission ID',
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'name' => 'id_mission',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => 'CHtml::link($data->id_applying_carer, array("carerAdmin/view", "id"=>$data->id_applying_carer))',
            'header' => 'Carer',
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'name' => 'id_applying_carer',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => 'CHtml::link($data->mission->booking->id_client, array("clientAdmin/view", "id"=>$data->mission->booking->id_client))',
            'header' => 'Client',
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'name' => 'id_applying_carer',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getStatusLabel()',
            'header' => 'Status',
            'headerHtmlOptions' => array('style' => 'width:60px'),
            'name' => 'status',
            'type' => 'raw',
        ),
//        array(
//            'class' => 'CDataColumn',
//            'value' => '($data->discarded) ? "Discarded" : "Visible";',
//            'header' => 'Discarded',
//            'headerHtmlOptions' => array('style' => 'width:100px'),
//            'name' => 'discarded',
//        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->mission->start_date_time);
            },
            'header' => 'Start',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->mission->end_date_time);
            },
            'header' => 'End',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = $data->getStatusLabel();
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:60px'),
            'header' => 'Carer Status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = $data->mission->getStatusLabel();
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:60px'),
            'header' => 'Mission Status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = $data->mission->getCompletionStatusLabel(Constants::USER_CLIENT);
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Completion Status',
            'type' => 'raw'
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
$model = new MissionCarers;
?>

<b>TESTING ONLY! Don't use the following in production!</b>
<br><br>
<i>Set selected row(s) to:</i>
<div class="row">
    <?php echo CHtml::dropDownList('status', '', $model->getStatusOptions()); ?>	
    <?php echo CHtml::button('Change status', array('submit' => 'updateStatuses')); ?>
</div>


<i>Set selected row(s) to:</i>
<div class="row">
    <?php echo CHtml::dropDownList('discarded', '', array(0 => 'Visible', 1 => 'Discarded')); ?>	
    <?php echo CHtml::button('Change discarded', array('submit' => 'updateDiscarded')); ?>
</div>

<i>Delete selected row(s):</i>
<div class="row">
    <?php echo CHtml::button('Delete', array('submit' => 'deleteSelected')); ?>
</div>

<i>Create an Apply relation</i>

<div class="row">
    <?php
    echo CHtml::label('Carer Id', 'carerId');
    echo CHtml::textField('carerId', '', array());
    ?>	
    <?php
    echo CHtml::label('Mission Id', 'missionId');
    echo CHtml::textField('missionId', '', array());
    ?>
    <?php echo CHtml::button('Create Apply', array('submit' => 'createApplyRelation')); ?>
</div>

<?php $this->endWidget(); ?>

<br /><br />
<hr>
<h1>All Shifts</h1>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
        ));
?>

<?php
$this->renderPartial('/mission/_missionsTable', array('modelMission' => $modelMission));
?>
<b>TESTING ONLY! Don't use the following in production!</b>
<br><br>

<i>Set selected row(s) to (status only! <b>no logic</b>):</i>
<div class="row">
    <?php echo CHtml::dropDownList('missionStatus', '', Mission::getStatusOptions(false)); ?>	
    <?php echo CHtml::button('Change shift status', array('submit' => 'updateMissionStatuses2')); ?>
</div>
<i>Cancel by admin <b>(refund)</b> selected row(s)</i>
<div class="row">
   
    <?php echo CHtml::button('Cancel By Admin', array('submit' => 'cancelByAdmin')); ?>
</div>

<?php $this->endWidget(); ?>