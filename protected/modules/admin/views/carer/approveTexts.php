
<h2>Texts to approve</h2>

<?php


$dataProvider = new CActiveDataProvider('CarerText');
$dataProvider->setData($texts);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'text-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'id' => 'autoId',
            'class' => 'CCheckBoxColumn',
            'selectableRows' => '50',
        ),
        'id',
        'id_carer',
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getStatusLabel()',
            'header' => 'Status',
            'name' => 'status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getActiveLabel()',
            'header' => 'Active',
            'name' => 'active',
            'type' => 'raw',
        ),
        'text',
        array(
            'class' => 'CButtonColumn',
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{approve} {reject}',
            'buttons' => array(
                'approve' => array('label' => 'Approve',
                    'url' => 'Yii::app()->createUrl("admin/carerAdmin/approveText", array("type"=>$data->type, "carerId"=>$data->id_carer))',
                ),
                'reject' => array('label' => 'Reject',
                    'url' => 'Yii::app()->createUrl("admin/carerAdmin/rejectText", array("type"=>$data->type, "carerId"=>$data->id_carer))',
                ),
            ),
        ),
    )
  )
);
?>