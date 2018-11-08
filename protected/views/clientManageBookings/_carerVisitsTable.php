<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'id' => Tables::CARER_VISITS_GRID,
    'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
    'template' => '{items}{pager}',
    'rowCssClassExpression' => '$data->getColor($row)',
    'columns' => array(
        array(
            'header' => Yii::t('texts', 'When'),
            'htmlOptions' => array('style' => 'width:150px'),
            'class' => 'CDataColumn', // can be omitted, default
            'name' => 'start_date',
            'value' => function($data, $row) {
                return $data->getDateTimeDuration();
                // return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time);
            },
            'type' => 'raw',
        ),
//        array(
//            'header' => Yii::t('texts', 'TABLES_HEADER_END'),
//            'htmlOptions' => array('style' => 'width:110px'),
//            'class' => 'CDataColumn', // can be omitted, default
//            'name' => 'end_date',
//            'value' => function($data, $row) {
//                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->end_date_time);
//            },
//            'type' => 'raw',
//        ),
        array(
            'header' => Yii::t('texts', 'TABLES_HEADER_LOCATION'),
            'htmlOptions' => array('style' => 'width:120px'),
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $serviceLocation = $data->serviceLocation;
                return $serviceLocation->displayShort();
            },
            'type' => 'raw',
        ),
        array(
            'header' => Yii::t('texts', 'TABLES_HEADER_USERS'),
            'htmlOptions' => array('style' => 'width:100px'),
            'class' => 'CDataColumn', // can be omitted, default
            'value' => function($data, $row) {
                return $data->displayServiceUsersText('<br />');
            },
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_SERVICE'),
            'htmlOptions' => array('style' => 'width:100px'),
            'value' => function($data, $row) {
                if ($data->type == Constants::LIVE_IN) {
                    return Yii::t('texts', 'LABEL_LIVE_IN_CARE');
                } else {
                    return Yii::t('texts', 'LABEL_HOURLY_CARE');
                }
            },
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn', // can be omitted, default
            'header' => Yii::t('texts', 'Carer'),
            'htmlOptions' => array('style' => 'width:100px'),
            'value' => function($data, $row) {
                return $data->getAssignedCarerName();
            },
            'type' => 'raw',
        ),
        Tables::getCarerVisitStatus(true),
        Tables::getCarerVisitButtonConfig(true, $scenario),
    ),
));
?>