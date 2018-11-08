<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'live-in-mission-grid',
    'pager' => 'LinkPager',
    'dataProvider' => $modelMission->search(),
    //'filter' => $modelMission,
    'columns' => array(
        array(
            'id' => 'autoIdMission',
            'class' => 'CCheckBoxColumn',
            'selectableRows' => '50',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->id',
            'header' => 'Id',
            'headerHtmlOptions' => array('style' => 'width:40px'),
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->id_booking',
            'header' => 'Id Booking',
            'headerHtmlOptions' => array('style' => 'width:60px'),
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time);
            },
            'header' => 'Start',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->end_date_time);
            },
            'header' => 'End',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = $data->getCompletionStatusLabel(Constants::USER_CLIENT);
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Completion Status',
            'name' => 'status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $label = $data->getStatusLabel();
                return $label;
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Status',
            'name' => 'status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return ($data->carer_credited ? 'true' : 'false');
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Carer Credited',
            'name' => 'carer_credited',
            'type' => 'raw',
        ),
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'label' => 'Select',
                    'url' => 'Yii::app()->createUrl("/admin/missionAdmin/viewMission", array("missionId"=>$data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small'),
                ),
            ),
        ),
    ),
));
?>
