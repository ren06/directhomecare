
<h2>Shifts of Carer <?php echo $carer->fullName ?></h2>

<?php
$missions = $carer->getAllAssignedMissions();

$dataProvider = new CActiveDataProvider('Mission');

$dataProvider->setData($missions);

$filter = new Mission();
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'mission-grid',
    'dataProvider' => $dataProvider,
    'filter' => $filter,
    'columns' => array(
        'id',
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->start_date);
            },
            'header' => 'Start Date',
                    'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getMissionChild()->getStart_Time();
            },
            'header' => 'Time',
                    'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convertDisplayDate($data->getMissionChild()->end_date);
            },
            'header' => 'End Date',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getMissionChild()->getEnd_Time();
            },
            'header' => 'Time',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getTypeLabel();
            },
            'header' => 'Type',
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
        ),
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{select}',
            'buttons' => array(
                'select' => array(
                    'label' => 'Select',
                    'url' => 'Yii::app()->createUrl("/admin/missionAdmin/viewMission", array("missionId"=>$data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small'),
                ),
            ),
        ),
    ),
));
?>